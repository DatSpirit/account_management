<?php

namespace App\Services;

use App\Models\User;
use App\Models\CoinkeyWallet;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Exception;

class CoinkeyService
{
    const MINUTES_PER_COINKEY = 1; // 1 coinkey = 1 phút
    
    /**
     * Quy đổi VND sang Coinkey
     */
    public function convertVndToCoinkey(float $vndAmount, float $exchangeRate): float
    {
        // exchangeRate = giá 1 coinkey bằng bao nhiêu VND
        return round($vndAmount / $exchangeRate, 2);
    }

    /**
     * Quy đổi Coinkey sang VND
     */
    public function convertCoinkeyToVnd(float $coinkeyAmount, float $exchangeRate): float
    {
        return round($coinkeyAmount * $exchangeRate, 2);
    }

    /**
     * Quy đổi phút sang Coinkey
     */
    public function convertMinutesToCoinkey(int $minutes): float
    {
        return $minutes * self::MINUTES_PER_COINKEY;
    }

    /**
     * Quy đổi Coinkey sang phút
     */
    public function convertCoinkeyToMinutes(float $coinkey): int
    {
        return (int) floor($coinkey / self::MINUTES_PER_COINKEY);
    }

    /**
     * Nạp coinkey từ giao dịch thanh toán thành công
     */
    public function depositFromTransaction(User $user, Transaction $transaction): void
    {
        if ($transaction->status !== 'success') {
            throw new Exception('Transaction must be successful');
        }

        $wallet = $user->getOrCreateWallet();
        
        // Lấy product để biết số coinkey
        $product = $transaction->product;
        if (!$product || !$product->coinkey_amount) {
            throw new Exception('Product coinkey amount not found');
        }

        DB::beginTransaction();
        try {
            $wallet->deposit(
                amount: $product->coinkey_amount,
                type: 'deposit',
                description: "Nạp {$product->coinkey_amount} coinkey từ giao dịch #{$transaction->order_code}",
                referenceType: Transaction::class,
                referenceId: $transaction->id
            );

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Mua sản phẩm bằng coinkey
     */
    public function purchaseWithCoinkey(User $user, Product $product, int $customMinutes = null): array
    {
        $wallet = $user->getOrCreateWallet();

        // Tính số coinkey cần
        if ($customMinutes) {
            $requiredCoinkey = $this->convertMinutesToCoinkey($customMinutes);
            $duration = $customMinutes;
        } else {
            $requiredCoinkey = $product->coinkey_amount;
            $duration = $product->duration_minutes;
        }

        if (!$wallet->hasBalance($requiredCoinkey)) {
            throw new Exception('Insufficient coinkey balance');
        }

        DB::beginTransaction();
        try {
            // Trừ coinkey
            $coinkeyTx = $wallet->withdraw(
                amount: $requiredCoinkey,
                type: 'purchase',
                description: "Mua {$product->name} ({$duration} phút)",
                referenceType: Product::class,
                referenceId: $product->id
            );

            // Tạo transaction record
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'order_code' => 'CK-' . strtoupper(uniqid()),
                'amount' => $requiredCoinkey,
                'status' => 'success',
                'description' => "Paid with {$requiredCoinkey} coinkey",
            ]);

            DB::commit();

            return [
                'success' => true,
                'transaction' => $transaction,
                'coinkey_transaction' => $coinkeyTx,
                'remaining_balance' => $wallet->balance,
            ];

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Hoàn tiền coinkey
     */
    public function refundCoinkey(User $user, Transaction $transaction, float $amount, string $reason): void
    {
        $wallet = $user->getOrCreateWallet();

        DB::beginTransaction();
        try {
            $wallet->refund(
                amount: $amount,
                description: "Hoàn tiền: {$reason}",
                referenceType: Transaction::class,
                referenceId: $transaction->id
            );

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Admin điều chỉnh số dư
     */
    public function adminAdjustBalance(User $user, float $amount, string $reason): void
    {
        $wallet = $user->getOrCreateWallet();

        DB::beginTransaction();
        try {
            if ($amount > 0) {
                $wallet->deposit(
                    amount: $amount,
                    type: 'admin_adjust',
                    description: "Admin adjust: {$reason}"
                );
            } else {
                $wallet->withdraw(
                    amount: abs($amount),
                    type: 'admin_adjust',
                    description: "Admin adjust: {$reason}"
                );
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}