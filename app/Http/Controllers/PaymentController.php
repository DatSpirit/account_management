<?php

namespace App\Http\Controllers;

use PayOS\PayOS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;


$payOS = new PayOS(
    env('PAYOS_CLIENT_ID'),
    env('PAYOS_API_KEY'),
    env('PAYOS_CHECKSUM_KEY')
);

class PaymentController extends Controller
{

    protected $payOS;

    public function __construct()
    {
        // Khởi tạo payOS trong constructor
        $this->payOS = new PayOS(
            env('PAYOS_CLIENT_ID'),
            env('PAYOS_API_KEY'),
            env('PAYOS_CHECKSUM_KEY')
        );
    }


    public function handlePayOSWebhook(Request $request)
    {
        $body = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                "error" => 1,
                "message" => "Invalid JSON payload"
            ], 400);
        }

        //  Ghi log webhook nhận được
        Log::info(' Webhook từ PayOS:', $body);

        try {
            //  Xác minh chữ ký webhook
            $this->payOS->verifyPaymentWebhookData($body);

            $data = $body['data'] ?? [];

            // Kiểm tra dữ liệu cần thiết
            if (!isset($data['orderCode']) || !isset($data['status'])) {
                Log::warning('⚠️ Webhook thiếu dữ liệu quan trọng', $data);
                return response()->json([
                    "error" => 1,
                    "message" => "Missing required fields"
                ], 400);
            }

            $orderCode = $data['orderCode'];
            $status = strtolower($data['status']); // "PAID", "CANCELLED", etc.

            //  Cập nhật trạng thái trong bảng transactions
            $transaction = Transaction::where('order_code', $orderCode)->first();

            if ($transaction) {
                $transaction->update([
                    'status' => match ($status) {
                        'paid' => 'success',
                        'cancelled' => 'failed',
                        default => 'pending',
                    },
                    'description' => $data['description'] ?? 'Webhook update',
                ]);

                Log::info("✅ Transaction #{$transaction->id} updated to {$transaction->status}");
            } else {
                // Nếu chưa tồn tại, ghi log dự phòng
                Transaction::create([
                    'user_id' => null,
                    'product_id' => null,
                    'order_code' => $orderCode,
                    'amount' => $data['amount'] ?? 0,
                    'status' => $status,
                    'description' => $data['description'] ?? 'Webhook received - new record',
                ]);

                Log::warning("⚠️ Transaction not found, created new for orderCode {$orderCode}");
            }

            return response()->json([
                "error" => 0,
                "message" => "Webhook verified & transaction updated successfully",
                "data" => $data
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Lỗi xử lý Webhook PayOS: ' . $e->getMessage());

            return response()->json([
                "error" => 1,
                "message" => "Invalid webhook data",
                "details" => $e->getMessage()
            ], 400);
        }
    }
}
