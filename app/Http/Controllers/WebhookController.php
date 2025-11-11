<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Lấy toàn bộ payload JSON từ webhook
        $payload = $request->json()->all(); // hoặc $request->all() nếu không chắc chắc là JSON

        // Log dữ liệu để debug
        \Log::info('🔔 🔔 🔔 Webhook payload received:', $payload);

        // cách xem dữ liệu: notepad storage/logs/laravel.log


        // 1️⃣ Log đầu vào
        Log::info('🔔 PayOS Webhook received', $request->all());

        try {
            $payload = $request->all();

            // 2️⃣ Lấy các trường theo structure PayOS
            $code = $payload['code'] ?? null;
            $desc = $payload['desc'] ?? null;
            $data = $payload['data'] ?? null;
            $signature = $payload['signature'] ?? $payload['sign'] ?? null; // PayOS có thể dùng 'sign' hoặc 'signature'

            Log::info('📦 Extracted fields', [
                'code' => $code,
                'desc' => $desc,
                'has_data' => !empty($data),
                'signature' => $signature ? substr($signature, 0, 20) . '...' : 'null'
            ]);

            if (!$signature || !$data) {
                Log::warning('⚠️ Missing signature or data');
                return response()->json(['error' => 0, 'message' => 'ok'], 200);
            }

            // 3️⃣ Xác thực chữ ký
            $isValid = $this->verifySignature($data, $signature);
            if (!$isValid) {
                // Log::warning('❌ Signature verification FAILED - Processing anyway for testing');
                return response()->json(['error'=>1,'message'=>'Invalid signature'], 401);
            } else {
                Log::info('✅ Signature verified successfully');
            }

            // 4️⃣ Lấy thông tin giao dịch từ data
            $orderCode = $data['orderCode'] ?? null;
            $amount = $data['amount'] ?? 0;
            $paymentCode = $data['code'] ?? $code;
            $description = $data['description'] ?? '';
            $status = $data['status'] ?? null; // PAID, CANCELLED, PENDING

            Log::info('💳 Payment details', [
                'orderCode' => $orderCode,
                'amount' => $amount,
                'paymentCode' => $paymentCode,
                'status' => $status,
                'description' => $description
            ]);

            if (!$orderCode) {
                Log::warning('⚠️ Missing orderCode');
                return response()->json(['error' => 0, 'message' => 'ok'], 200);
            }

            // 5️⃣ Tìm transaction trong database
            $transaction = Transaction::where('order_code', $orderCode)->first();

            if (!$transaction) {
                Log::warning("⚠️ Transaction not found for orderCode: {$orderCode}");
                // Tạo transaction mới
                $transaction = Transaction::create([
                    'user_id' => null,
                    'product_id' => null,
                    'order_code' => $orderCode,
                    'amount' => $amount,
                    'status' => 'pending',
                    'description' => $description
                ]);
                Log::info("🆕 Created new transaction", ['id' => $transaction->id]);
            }

            // 6️⃣ Xác định trạng thái mới
            $newStatus = $this->determineStatus($status, $paymentCode);
            $oldStatus = $transaction->status;

            Log::info('🔄 Status mapping', [
                'original_status' => $status,
                'payment_code' => $paymentCode,
                'new_status' => $newStatus,
                'old_transaction_status' => $oldStatus
            ]);

            if ($newStatus === 'cancelled') {
                Log::warning("⚠️ Transaction {$orderCode} has been CANCELLED!");
            }

            // 7️⃣ Cập nhật transaction
            $transaction->update([
                'status' => $newStatus,
                'amount' => $amount,
                'description' => "{$desc} - {$description}"
            ]);

            Log::info("✅ Transaction updated successfully", [
                'id' => $transaction->id,
                'orderCode' => $orderCode,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'amount' => $amount
            ]);

            // 8️⃣ Response
            return response()->json([
                'error' => 0,
                'message' => 'ok',
                'data' => [
                    'orderCode' => $orderCode,
                    'status' => $newStatus
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('❌ Webhook error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 0, 'message' => 'ok'], 200);
        }
    }

    /**
     * Xác định trạng thái từ status hoặc code
     */
    private function determineStatus(?string $status, ?string $code): string
    {
        if ($status) {
            $statusUpper = strtoupper($status);
            if ($statusUpper === 'PAID') return 'success';
            if ($statusUpper === 'CANCELLED') return 'cancelled';
            if ($statusUpper === 'PENDING') return 'pending';
            return 'failed';
        }

        if ($code) {
            return match($code) {
                '00' => 'success',
                '01' => 'failed',
                '02' => 'pending',
                default => 'cancelled'
            };
        }

        return 'pending';
    }

    /**
     * Xác thực chữ ký webhook
     */
    private function verifySignature(array $data, string $receivedSignature): bool
    {
        try {
            $checksumKey = env('PAYOS_CHECKSUM_KEY');

            if (!$checksumKey) {
                Log::error('❌ PAYOS_CHECKSUM_KEY not set in .env');
                return false;
            }

            Log::info('🔐 Starting signature verification', [
                'checksumKey_length' => strlen($checksumKey),
                'received_signature' => $receivedSignature
            ]);

            // Cách 1: Sắp xếp và dùng http_build_query
            ksort($data);
            $dataStr1 = http_build_query($data);
            $signature1 = hash_hmac('sha256', $dataStr1, $checksumKey);

            // Cách 2: JSON encode
            ksort($data);
            $dataStr2 = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $signature2 = hash_hmac('sha256', $dataStr2, $checksumKey);

            // Cách 3: Manual concatenation
            $sortedKeys = array_keys($data);
            sort($sortedKeys);
            $dataStr3 = '';
            foreach ($sortedKeys as $key) {
                $dataStr3 .= $key . '=' . $data[$key] . '&';
            }
            $dataStr3 = rtrim($dataStr3, '&');
            $signature3 = hash_hmac('sha256', $dataStr3, $checksumKey);

            Log::info('🔐 Computed signatures', [
                'method1_query' => $signature1,
                'method2_json' => $signature2,
                'method3_manual' => $signature3,
                'received' => $receivedSignature,
                'data_str1' => substr($dataStr1, 0, 100),
                'data_str2' => substr($dataStr2, 0, 100),
                'data_str3' => substr($dataStr3, 0, 100)
            ]);

            $isValid = hash_equals($signature1, $receivedSignature)
                    || hash_equals($signature2, $receivedSignature)
                    || hash_equals($signature3, $receivedSignature);

            Log::info('🔐 Verification result', [
                'isValid' => $isValid,
                'method1_match' => hash_equals($signature1, $receivedSignature),
                'method2_match' => hash_equals($signature2, $receivedSignature),
                'method3_match' => hash_equals($signature3, $receivedSignature)
            ]);

            return $isValid;

        } catch (\Exception $e) {
            Log::error('❌ Signature verification exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
}
