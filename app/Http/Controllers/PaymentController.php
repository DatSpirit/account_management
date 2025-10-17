<?php

namespace App\Http\Controllers;

use PayOS\PayOS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;



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

        // Log dữ liệu nhận được để theo dõi
        Log::info('Webhook từ PayOS:', $body);

        // Handle webhook test
        if (in_array($body["data"]["description"], ["Ma giao dich thu nghiem", "VQRIO123"])) {
            return response()->json([
                "error" => 0,
                "message" => "Ok",
                "data" => $body["data"]
            ]);
        }

        try {
            $this->payOS->verifyPaymentWebhookData($body);

               return response()->json([
                "error" => 0,
                "message" => "Webhook verified & processed successfully",
                "data" => $body["data"]
            ]);

        } catch (\Exception $e) {
            Log::error('Webhook PayOS lỗi xác minh: ' . $e->getMessage());

            return response()->json([
                "error" => 1,
                "message" => "Invalid webhook data",
                "details" => $e->getMessage()
            ], 400);
        }
    }
}
