<?php

namespace App\Http\Controllers;

use PayOS\PayOS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;

class PaymentController extends Controller
{
    protected $payOS;

    public function __construct()
    {
        // Khởi tạo PayOS từ biến môi trường
        $this->payOS = new PayOS(
            env('PAYOS_CLIENT_ID'),
            env('PAYOS_API_KEY'),
            env('PAYOS_CHECKSUM_KEY')
        );
    }

    /**
     * Lấy thông tin thanh toán theo orderCode
     */
    public function getPaymentInfo($orderCode)
    {
        try {
            $response = $this->payOS->getPaymentLinkInformation($orderCode);
            
            Log::info("Payment info retrieved for order: {$orderCode}", $response);
            
            return response()->json([
                "error" => 0,
                "message" => "Success",
                "data" => $response
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Error getting payment info: ' . $e->getMessage());
            
            return response()->json([
                "error" => 1,
                "message" => "Failed to get payment info",
                "details" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Hủy link thanh toán
     */
    public function cancelPayment($orderCode, Request $request)
    {
        try {
            $cancelBody = [
                'cancellationReason' => $request->input('reason', 'Người dùng hủy đơn hàng')
            ];
            
            $response = $this->payOS->cancelPaymentLink($orderCode, $cancelBody);
            
            // Cập nhật trạng thái transaction
            $transaction = Transaction::where('order_code', $orderCode)->first();
            if ($transaction) {
                $transaction->update([
                    'status' => 'cancelled',
                    'description' => 'Payment cancelled by user'
                ]);
            }
            
            Log::info("Payment cancelled for order: {$orderCode}");
            
            return response()->json([
                "error" => 0,
                "message" => "Payment cancelled successfully",
                "data" => $response
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Error cancelling payment: ' . $e->getMessage());
            
            return response()->json([
                "error" => 1,
                "message" => "Failed to cancel payment",
                "details" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Xác minh webhook signature (helper method)
     */
    private function verifyWebhookSignature($data, $signature)
    {
        $checksumKey = env('PAYOS_CHECKSUM_KEY');
        
        // Sắp xếp keys theo alphabet
        ksort($data);
        
        // Tạo query string
        $dataStr = http_build_query($data);
        
        // Tính signature
        $computedSignature = hash_hmac('sha256', $dataStr, $checksumKey);
        
        return hash_equals($signature, $computedSignature);
    }
}