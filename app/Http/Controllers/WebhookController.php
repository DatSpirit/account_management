<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Exception;

class WebhookController extends Controller
{
    /**
     * Xử lý webhook từ PayOS.
     * Áp dụng Cache Lock (Rate Limiting) ngay sau khi trích xuất OrderCode.
     */
    public function handleWebhook(Request $request)
    {
        $startTime = microtime(true);
        $requestId = uniqid('webhook_', true);
        $orderCode = null; // Khởi tạo biến orderCode

        // Log request info đầu tiên
        Log::info("🔔 [{$requestId}] Webhook received", [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        try {
            // Lấy toàn bộ payload ngay lập tức
            $payload = $request->all();


            // Log dữ liệu để debug
            // \Log::info('🔔 🔔 🔔 Webhook payload received:', $payload);

            
            // ===================================
            // 1️⃣ VALIDATE PAYLOAD STRUCTURE & EXTRACT ORDER CODE
            // ===================================
            if (!isset($payload['data'], $payload['signature'])) {
                Log::warning("⚠️ [{$requestId}] Invalid payload structure");
                return response()->json(['error' => 0, 'message' => 'ok'], 200);
            }

            // Lấy orderCode ngay sau khi xác thực cấu trúc cơ bản
            $orderCode = $payload['data']['orderCode'] ?? null;
            
            if (!$orderCode) {
                Log::warning("⚠️ [{$requestId}] Missing orderCode");
                return response()->json(['error' => 0, 'message' => 'ok'], 200);
            }
            
            // ===================================
            // 2️⃣ CACHE-BASED IMMEDIATE RATE LIMITING
            // Chặn ngay lập tức TẤT CẢ các webhook có cùng orderCode trong X giây.
            // ===================================
            $cacheKey = "webhook_processing:{$orderCode}";
            $lockDurationSeconds = 300; // Khóa trong 300 giây

            // Sử dụng Cache::add() để tạo lock bất khả xâm phạm.
            if (!Cache::add($cacheKey, $requestId, $lockDurationSeconds)) {
                Log::warning("🚫 [{$requestId}] Duplicate webhook blocked (Early)", [
                    'orderCode' => $orderCode,
                    'current_lock_holder' => Cache::get($cacheKey)
                ]);
                
                // Trả về 200 OK ngay lập tức.
                return response()->json([
                    'error' => 0,
                    'message' => 'cache_lock_blocked_early',
                    'data' => ['orderCode' => $orderCode]
                ], 200);
            }
            Log::info("🔒 [{$requestId}] Acquired cache lock for processing");

            // ===================================
            // 3️⃣ VERIFY SIGNATURE 
            // ===================================
            $data = $payload['data'];
            $signature = $payload['signature'];
            $code = $payload['code'] ?? null;
            $desc = $payload['desc'] ?? null;
            
            if (!$this->verifySignature($data, $signature)) {
                Log::error("❌ [{$requestId}] INVALID SIGNATURE - Possible attack!", [
                    'ip' => $request->ip(),
                    'signature' => substr($signature, 0, 20) . '...'
                ]);
                
                // Giải phóng lock Cache trước khi trả về lỗi.
                Cache::forget($cacheKey);
                return response()->json(['error' => 1, 'message' => 'Invalid signature'], 401);
            }

            Log::info("✅ [{$requestId}] Signature verified");

            // ===================================
            // 4️⃣ EXTRACT PAYMENT DATA
            // ===================================
            $amount = $data['amount'] ?? 0;
            $paymentCode = $data['code'] ?? $code;
            $description = $data['description'] ?? '';
            $status = $data['status'] ?? null;
            
            // Payment details
            $paymentReference = $data['reference'] ?? null;
            $accountNumber = $data['accountNumber'] ?? null;
            $counterAccountName = $data['counterAccountName'] ?? null;
            $counterAccountNumber = $data['counterAccountNumber'] ?? null;
            $counterAccountBankId = $data['counterAccountBankId'] ?? null;
            $counterAccountBankName = $data['counterAccountBankName'] ?? null;
            $paymentLinkId = $data['paymentLinkId'] ?? null;
            $transactionDateTime = $data['transactionDateTime'] ?? null;
            
            Log::info("💳 [{$requestId}] Payment details", [
                'orderCode' => $orderCode,
                'amount' => $amount,
                'reference' => $paymentReference,
                'status' => $status,
            ]);

            // ===================================
            // 5️⃣ DATABASE TRANSACTION WITH LOCKING
            // ===================================
            DB::beginTransaction();
            
            try {
                // Tìm transaction với row lock
                $transaction = Transaction::where('order_code', $orderCode)
                    ->lockForUpdate()
                    ->first();

                // ===================================
                // 6️⃣ CREATE NEW TRANSACTION IF NOT EXISTS
                // ===================================
                if (!$transaction) {
                    Log::info("🆕 [{$requestId}] Creating new transaction", [
                        'orderCode' => $orderCode
                    ]);
                    
                    $transaction = Transaction::create([
                        'user_id' => null,
                        'product_id' => null,
                        'order_code' => $orderCode,
                        'amount' => $amount,
                        'status' => 'pending',
                        'description' => $description,
                        'is_processed' => false,
                        'payment_reference' => $paymentReference,
                        'account_number' => $accountNumber,
                        'counter_account_name' => $counterAccountName,
                        'counter_account_number' => $counterAccountNumber,
                        'counter_account_bank_id' => $counterAccountBankId,
                        'counter_account_bank_name' => $counterAccountBankName,
                        'payment_link_id' => $paymentLinkId,
                        'transaction_datetime' => $transactionDateTime ? date('Y-m-d H:i:s', strtotime($transactionDateTime)) : null,
                        'currency' => $data['currency'] ?? 'VND',
                    ]);
                }

                // ===================================
                // 7️⃣ CHECK DUPLICATE BY SIGNATURE (Lớp bảo vệ vĩnh viễn trong DB)
                // ===================================
                if ($transaction->isDuplicateWebhook($signature)) {
                    Log::warning("🚫 [{$requestId}] Duplicate webhook ignored by signature (DB check)", [
                        'orderCode' => $orderCode,
                        'current_status' => $transaction->status,
                        'is_processed' => $transaction->is_processed,
                        'processed_at' => $transaction->processed_at,
                        'signature_match' => true
                    ]);
                    
                    DB::commit();
                    
                    return response()->json([
                        'error' => 0,
                        'message' => 'duplicate_ignored',
                        'data' => [
                            'orderCode' => $orderCode,
                            'status' => $transaction->status,
                            'processed_at' => $transaction->processed_at
                        ]
                    ], 200);
                }

                // ===================================
                // 8️⃣ DETERMINE NEW STATUS
                // ===================================
                $newStatus = $this->determineStatus($status, $paymentCode);
                $oldStatus = $transaction->status;

                Log::info("🔄 [{$requestId}] Status mapping", [
                    'orderCode' => $orderCode,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'was_processed' => $transaction->is_processed
                ]);

                // ===================================
                // 9️⃣ UPDATE TRANSACTION
                // ===================================
                $transaction->update([
                    'status' => $newStatus,
                    'amount' => $amount,
                    'description' => $this->sanitizeDescription("{$desc} - {$description}"),
                    'payment_reference' => $paymentReference,
                    'account_number' => $accountNumber,
                    'counter_account_name' => $counterAccountName,
                    'counter_account_number' => $counterAccountNumber,
                    'counter_account_bank_id' => $counterAccountBankId,
                    'counter_account_bank_name' => $counterAccountBankName,
                    'payment_link_id' => $paymentLinkId,
                    'transaction_datetime' => $transactionDateTime ? date('Y-m-d H:i:s', strtotime($transactionDateTime)) : null,
                    'currency' => $data['currency'] ?? 'VND',
                    'response_data' => $data, // Backup full data
                ]);

                // ===================================
                // 🔟 MARK AS PROCESSED
                // ===================================
                $transaction->markAsProcessed($signature, $payload);

                $processingTime = round((microtime(true) - $startTime) * 1000, 2);

                Log::info("✅ [{$requestId}] Transaction processed successfully", [
                    'id' => $transaction->id,
                    'orderCode' => $orderCode,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'amount' => $amount,
                    'reference' => $paymentReference,
                    'processed_at' => $transaction->processed_at,
                    'processing_time_ms' => $processingTime
                ]);

                DB::commit();
                Cache::forget($cacheKey); // Giải phóng lock Cache

                return response()->json([
                    'error' => 0,
                    'message' => 'ok',
                    'data' => [
                        'orderCode' => $orderCode,
                        'status' => $newStatus,
                        'processed_at' => $transaction->processed_at->toIso8601String(),
                        'processing_time_ms' => $processingTime
                    ]
                ], 200);

            } catch (Exception $e) {
                DB::rollBack();
                Cache::forget($cacheKey); // Giải phóng lock Cache
                throw $e;
            }

        } catch (Exception $e) {
            $processingTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error("❌ [{$requestId}] Webhook processing error", [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'orderCode' => $orderCode ?? 'unknown',
                'processing_time_ms' => $processingTime
            ]);

            // Nếu orderCode đã được trích xuất và có thể đã thiết lập lock, đảm bảo lock được giải phóng.
            if ($orderCode) {
                 Cache::forget("webhook_processing:{$orderCode}");
            }
            
            // Luôn trả về 200 OK theo yêu cầu của cổng thanh toán.
            return response()->json(['error' => 0, 'message' => 'ok'], 200);
        }
    }

    /**
     * Xác định trạng thái từ status hoặc code
     */
    private function determineStatus(?string $status, ?string $code): string
    {
        // Ưu tiên status text
        if ($status) {
            return match(strtoupper($status)) {
                'PAID' => 'success',
                'CANCELLED' => 'cancelled',
                'PENDING' => 'pending',
                default => 'failed'
            };
        }

        // Fallback sang code
        return match($code) {
            '00' => 'success',
            '01' => 'failed',
            '02' => 'pending',
            default => 'cancelled'
        };
    }

    /**
     * Xác thực chữ ký webhook
     */
    private function verifySignature(array $data, string $receivedSignature): bool
    {
        try {
            $checksumKey = env('PAYOS_CHECKSUM_KEY');

            if (!$checksumKey) {
                Log::critical('❌ PAYOS_CHECKSUM_KEY not configured!');
                return false;
            }

            // Sắp xếp keys theo alphabet
            ksort($data);

            // Tạo chuỗi data
            $dataStr = '';
            foreach ($data as $key => $value) {
                $dataStr .= $key . '=' . $value . '&';
            }
            $dataStr = rtrim($dataStr, '&');

            // Tính HMAC-SHA256
            $computedSignature = hash_hmac('sha256', $dataStr, $checksumKey);

            // So sánh an toàn
            return hash_equals($computedSignature, $receivedSignature);

        } catch (Exception $e) {
            Log::error('❌ Signature verification exception', [
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Sanitize description
     */
    private function sanitizeDescription(string $description): string
    {
        $description = strip_tags($description);
        // Loại bỏ ký tự không phải chữ, số, khoảng trắng, hoặc ký tự cơ bản (-,_,.,!,?)
        $description = preg_replace('/[^\p{L}\p{N}\s\-_.,!?]/u', '', $description);
        return substr($description, 0, 500);
    }
}