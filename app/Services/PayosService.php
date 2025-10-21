<?php

namespace App\Services;

use PayOS\PayOS;

class PayosService
{
    protected $payOS;

    public function __construct()
    {
        $this->payOS = new PayOS(
            env('PAYOS_CLIENT_ID'),
            env('PAYOS_API_KEY'),
            env('PAYOS_CHECKSUM_KEY')
        );
    }

    public function createPaymentLink(array $data)
    {
        // Kiểm tra payload trước khi gửi (để debug)
        // dd($data);

        $response = $this->payOS->createPaymentLink([
            'orderCode' => $data['orderCode'],
            'amount' => $data['amount'],
            'description' => $data['description'],
            'returnUrl' => $data['returnUrl'],
            'cancelUrl' => $data['cancelUrl'],
            'items' => $data['items'] // ✅ Phải là 'items' dạng mảng
        ]);

        return $response['checkoutUrl'] ?? '/';
    }
}
