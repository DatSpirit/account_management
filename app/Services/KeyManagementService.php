<?php

namespace App\Services;

use App\Models\User;
use App\Models\Product;
use App\Models\ProductKey;
use App\Models\Transaction;
use App\Models\KeyValidationLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class KeyManagementService
{
    protected CoinkeyService $coinkeyService;

    public function __construct(CoinkeyService $coinkeyService)
    {
        $this->coinkeyService = $coinkeyService;
    }

    /**
     * ✅ Tạo key từ gói sẵn có 
     */
    public function createKeyFromPackage(User $user, Product $product, ?Transaction $transaction = null): ProductKey
    {
        if ($product->product_type !== 'package') {
            throw new Exception('Product must be a package type');
        }

        DB::beginTransaction();
        try {
            $key = ProductKey::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'transaction_id' => $transaction?->id,
                'key_code' => ProductKey::generateFormattedKey(),
                'key_type' => 'auto_generated',
                'duration_minutes' => $product->duration_minutes,
                'key_cost' => $product->coinkey_amount,  // ✅ Lưu giá gốc
                'status' => 'active',
            ]);

            // ✅ Auto activate
            $key->activate();

            // ✅ Tăng sold count (nếu Product có field này)
            if (method_exists($product, 'increment')) {
                $product->increment('sold_count');
            }

            DB::commit();

            Log::info("Key created successfully", [
                'key_id' => $key->id,
                'key_code' => $key->key_code,
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);

            return $key;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to create key from package", [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            throw $e;
        }
    }

    /**
     * Tạo key tùy chỉnh
     */
    public function createCustomKey(
        User $user,
        string $customKeyCode,
        int $durationMinutes,
        ?Product $baseProduct = null,
        ?string $assignedToEmail = null
    ): ProductKey {
        // Kiểm tra key đã tồn tại chưa
        if (ProductKey::where('key_code', $customKeyCode)->exists()) {
            throw new Exception('Key code already exists');
        }

        // Tính giá
        $coinkeyRequired = $this->coinkeyService->convertMinutesToCoinkey($durationMinutes);

        // Kiểm tra số dư
        $wallet = $user->getOrCreateWallet();
        if (!$wallet->hasBalance($coinkeyRequired)) {
            throw new Exception("Insufficient balance. Required: {$coinkeyRequired} coinkey");
        }

        DB::beginTransaction();
        try {
            // Trừ coinkey
            $wallet->withdraw(
                amount: $coinkeyRequired,
                type: 'purchase',
                description: "Create custom key: {$customKeyCode} ({$durationMinutes} minutes)",
                referenceType: 'ProductKey', 
                referenceId: null 
            );

            // Tạo key
            $key = ProductKey::create([
                'user_id' => $user->id,
                'product_id' => $baseProduct?->id,
                'key_code' => $customKeyCode,
                'key_type' => 'custom',
                'duration_minutes' => $durationMinutes,
                'key_cost' => $coinkeyRequired,
                'status' => 'active',
                'assigned_to_email' => $assignedToEmail,
            ]);

            // Auto activate
            $key->activate();

            DB::commit();
            return $key;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Validate key qua API
     */
    public function validateKey(
        string $keyCode,
        ?string $ipAddress = null,
        ?string $userAgent = null,
        ?string $deviceId = null
    ): array {
        $key = ProductKey::where('key_code', $keyCode)->first();

        if (!$key) {
            $this->logValidation(
                null,
                $keyCode,
                'invalid',
                'Key not found',
                $ipAddress,
                $userAgent,
                $deviceId
            );

            return [
                'valid' => false,
                'status' => 'invalid',
                'message' => 'Key not found',
            ];
        }

        // Kiểm tra trạng thái
        if ($key->isSuspended()) {
            $this->logValidation(
                $key->id,
                $keyCode,
                'suspended',
                'Key is suspended',
                $ipAddress,
                $userAgent,
                $deviceId
            );

            return [
                'valid' => false,
                'status' => 'suspended',
                'message' => 'Key is suspended',
            ];
        }

        if ($key->isExpired()) {
            $key->update(['status' => 'expired']);

            $this->logValidation(
                $key->id,
                $keyCode,
                'expired',
                'Key has expired',
                $ipAddress,
                $userAgent,
                $deviceId
            );

            return [
                'valid' => false,
                'status' => 'expired',
                'message' => 'Key has expired',
                'expired_at' => $key->expires_at?->toIso8601String(),
            ];
        }

        // Key hợp lệ
        $key->increment('validation_count');
        $key->update(['last_validated_at' => now()]);

        $this->logValidation(
            $key->id,
            $keyCode,
            'success',
            'Key is valid',
            $ipAddress,
            $userAgent,
            $deviceId,
            [
                'product_id' => $key->product_id,
                'user_id' => $key->user_id,
                'expires_at' => $key->expires_at?->toIso8601String(),
                'remaining_minutes' => $key->getRemainingMinutes(),
            ]
        );

        return [
            'valid' => true,
            'status' => 'success',
            'message' => 'Key is valid',
            'data' => [
                'key_id' => $key->id,
                'product_id' => $key->product_id,
                'expires_at' => $key->expires_at?->toIso8601String(),
                'remaining_minutes' => $key->getRemainingMinutes(),
                'remaining_days' => $key->getRemainingDays(),
            ],
        ];
    }

    /**
     * Gia hạn key
     */
    public function extendKey(User $user, ProductKey $key, int $additionalMinutes): ProductKey
    {
        // Kiểm tra quyền sở hữu
        if ($key->user_id !== $user->id) {
            throw new Exception('You do not own this key');
        }

        $coinkeyRequired = $this->coinkeyService->convertMinutesToCoinkey($additionalMinutes);
        $wallet = $user->getOrCreateWallet();

        if (!$wallet->hasBalance($coinkeyRequired)) {
            throw new Exception("Insufficient balance. Required: {$coinkeyRequired} coinkey");
        }

        DB::beginTransaction();
        try {
            $wallet->withdraw(
                amount: $coinkeyRequired,
                type: 'purchase',
                description: "Extend key {$key->key_code} by {$additionalMinutes} minutes",
                referenceType: ProductKey::class,
                referenceId: $key->id
            );

            $key->extend($additionalMinutes);
            $key->coinkey_cost += $coinkeyRequired;
            $key->save();

            DB::commit();
            return $key->fresh();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Log validation
     */
    private function logValidation(
        ?int $keyId,
        string $keyCode,
        string $result,
        string $message,
        ?string $ipAddress,
        ?string $userAgent,
        ?string $deviceId,
        ?array $responseData = null
    ): void {
        KeyValidationLog::create([
            'product_key_id' => $keyId,
            'key_code' => $keyCode,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'device_id' => $deviceId,
            'validation_result' => $result,
            'validation_message' => $message,
            'response_data' => $responseData,
            'validated_at' => now(),
        ]);
    }
}
