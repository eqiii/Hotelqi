<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class MidtransService
{
    /**
     * Inisialisasi konfigurasi Midtrans.
     */
    protected function init(): void
    {
        Config::$serverKey    = config('mdtrans.server_key');
        Config::$isProduction = config('mdtrans.is_production');
        Config::$isSanitized  = config('mdtrans.is_sanitized');
        Config::$is3ds        = config('mdtrans.is_3ds');
    }

    /**
     * Membuat Snap Token untuk pembayaran
     */
    public function createSnapToken(array $params): string
    {
        $this->init();

        return Snap::getSnapToken($params);
    }

    /**
     * Mendapatkan notifikasi dari Midtrans (Callback)
     *
     * Midtrans biasanya mengirim payload callback lewat request body.
     */
    public function getNotification(array $payload = []): Notification
    {
        $this->init();

        return new Notification($payload);
    }


    /**
     * Membuat parameter untuk Snap Midtrans
     */
    public function buildSnapParams(string $orderId, int $amount, string $customerName, string $customerEmail): array
    {
        return [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $customerName,
                'email'      => $customerEmail,
            ],
            'callbacks' => [
                'finish' => route('payment.finish'),
            ],
        ];
    }
}
