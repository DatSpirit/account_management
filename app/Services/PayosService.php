<?php
namespace App\Services;

use PayOS\PayOS;

class PayosService
{
    protected PayOS $payos;

    public function __construct()
    {
        $clientId = env('PAYOS_CLIENT_ID');
        $apiKey = env('PAYOS_API_KEY');
        $checksumKey = env('PAYOS_CHECKSUM_KEY');

        $this->payos = new PayOS($clientId, $apiKey, $checksumKey);
    }

    /**
     * Tạo payment link đơn giản
     * @param array $data
     * @return array
     */
    public function createPaymentLink(array $data): array
    {
        // $data: orderCode, amount, description, returnUrl, cancelUrl, buyerEmail...
        return $this->payos->createPaymentLink($data);
    }
}
