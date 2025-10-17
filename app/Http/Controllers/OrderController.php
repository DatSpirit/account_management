<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayOS\PayOS;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $payOS;

    public function __construct()
    {
        // Khởi tạo PayOS khi controller được gọi
        $this->payOS = new PayOS(
            env('PAYOS_CLIENT_ID'),
            env('PAYOS_API_KEY'),
            env('PAYOS_CHECKSUM_KEY')
        );
    }

    /**
     * Tạo đơn hàng và link thanh toán
     */
    public function createOrder(Request $request)
    {
        $body = $request->all();
        $body["amount"] = intval($body["amount"]);
        $body["orderCode"] = intval(substr(strval(microtime(true) * 100000), -6));

        try {
            $response = $this->payOS->createPaymentLink($body);
            return response()->json([
                "error" => 0,
                "message" => "Success",
                "data" => $response["checkoutUrl"]
            ]);
        } catch (\Throwable $th) {
            return $this->handleException($th);
        }
    }

    /**
     * Lấy thông tin link thanh toán của 1 đơn hàng
     */
    public function getPaymentLinkInfoOfOrder(string $id)
    {
        try {
            $response = $this->payOS->getPaymentLinkInformation($id);
            return response()->json([
                "error" => 0,
                "message" => "Success",
                "data" => $response["data"]
            ]);
        } catch (\Throwable $th) {
            return $this->handleException($th);
        }
    }

    /**
     * Hủy link thanh toán
     */
    public function cancelPaymentLinkOfOrder(Request $request, string $id)
    {
        $body = json_decode($request->getContent(), true);
        $cancelBody = (is_array($body) && !empty($body["cancellationReason"]))
            ? $body
            : ["cancellationReason" => "Người dùng hủy đơn hàng"];

        try {
            $response = $this->payOS->cancelPaymentLink($id, $cancelBody);
            return response()->json([
                "error" => 0,
                "message" => "Success",
                "data" => $response["data"]
            ]);
        } catch (\Throwable $th) {
            return $this->handleException($th);
        }
    }

    /**
     * Xử lý lỗi chung
     */
    private function handleException(\Throwable $th)
    {
        Log::error('PayOS Error: ' . $th->getMessage());

        return response()->json([
            "error" => 1,
            "message" => "Error",
            "details" => $th->getMessage()
        ], 500);
    }
}
