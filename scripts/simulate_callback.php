<?php
// Simulate a Midtrans server notification by invoking the controller directly
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

use Illuminate\Http\Request;

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Boot the app (optional)
$app->make(Illuminate\Foundation\Console\Kernel::class)->bootstrap();

$booking = \App\Models\Booking::whereHas('payment')->latest()->first();
if (!$booking) {
    echo "No booking with payment found.\n";
    exit(1);
}

$bookingId = $booking->id;
$payload = [
    'order_id' => "booking-{$bookingId}",
    'transaction_status' => 'settlement',
    'fraud_status' => null,
    'transaction_id' => 'midtrans-sim-' . uniqid(),
];

$request = Request::create('/payment/callback', 'POST', $payload);

$controller = $app->make(\App\Http\Controllers\MidtransCallbackController::class);
$response = $controller->handle($request);

echo "Callback HTTP status: " . $response->getStatusCode() . "\n";

$payment = \App\Models\Payment::where('booking_id', $bookingId)->first();
if ($payment) {
    echo "Payment ID: {$payment->id}\n";
    echo "payment_status: {$payment->payment_status}\n";
    echo "midtrans_transaction_id: {$payment->midtrans_transaction_id}\n";
    echo "paid_at: " . ($payment->paid_at ? $payment->paid_at->toDateTimeString() : 'NULL') . "\n";
} else {
    echo "No payment record found for booking {$bookingId}\n";
}

exit(0);
