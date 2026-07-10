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
    /**
     * Returns a Midtrans\Notification instance when reading from php://input.
     * If an array payload is provided (for testing), returns a Notification-like object.
     *
     * Note: keep return type flexible to support both SDK Notification and test payloads.
     */
    public function getNotification(array|string $payload = null)
    {
        $this->init();

        // Midtrans\Notification expects a stream/filename that contains JSON.
        // If caller passed an array (from a Request), wrap it into a data:// stream.
        if (is_array($payload) && !empty($payload)) {
            // Build a lightweight object that mimics the properties used by our code.
            // Include signature fields so tests/simulations can verify payloads.
            return (object) [
                'order_id' => $payload['order_id'] ?? null,
                'transaction_status' => $payload['transaction_status'] ?? null,
                'fraud_status' => $payload['fraud_status'] ?? null,
                'transaction_id' => $payload['transaction_id'] ?? null,
                'status_code' => $payload['status_code'] ?? null,
                'gross_amount' => $payload['gross_amount'] ?? null,
                'signature_key' => $payload['signature_key'] ?? null,
            ];
        }

        // Fallback: let Notification read from php://input
        return new Notification();
    }


    /**
     * Membuat parameter untuk Snap Midtrans
     */
    public function buildSnapParams(string $orderId, int $amount, string $customerName, string $customerEmail, ?string $finishUrl = null): array
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
                'finish' => $finishUrl ?? route('payment.finish'),
            ],
        ];
    }

    /**
     * Verify that a notification payload was genuinely sent by Midtrans.
     *
     * Midtrans signs every notification with:
     *   sha512(order_id + status_code + gross_amount + ServerKey)
     */
    public function verifySignature(object $notification): bool
    {
        $orderId = $notification->order_id ?? null;
        $statusCode = $notification->status_code ?? null;
        $grossAmount = $notification->gross_amount ?? null;
        $signatureKey = $notification->signature_key ?? null;

        if (!$orderId || $statusCode === null || $grossAmount === null || !$signatureKey) {
            return false;
        }

        $serverKey = config('mdtrans.server_key');
        $expected = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($expected, $signatureKey);
    }
}
