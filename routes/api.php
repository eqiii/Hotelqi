<?php

use App\Http\Controllers\MidtransCallbackController;
use Illuminate\Support\Facades\Route;

// Midtrans Notification Webhook (Tanpa CSRF & Auth)
Route::post('/midtrans/callback', [MidtransCallbackController::class, 'handle']);