<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminDashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// ==================== LANDING PAGE (PUBLIK) ====================
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/rooms', [LandingController::class, 'rooms'])->name('rooms');
Route::get('/rooms/{roomType}', [LandingController::class, 'roomDetail'])->name('rooms.detail');
Route::get('/faq', [LandingController::class, 'faq'])->name('faq');

// ==================== AUTH ROUTES ====================
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Resend verification email (gunakan controller kamu biar konsisten)
    Route::post('/email/verification-notification', [AuthController::class, 'resendVerification'])
        ->name('verification.send');
});

// ==================== EMAIL VERIFICATION ====================
Route::middleware(['auth', 'throttle:6,1'])->group(function () {
    Route::get('/verify-email', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/verify-email/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect()->route('user.dashboard')
            ->with('success', '✅ Email berhasil diverifikasi! Selamat datang di ' . hotel_name());
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
});

// ==================== USER / GUEST AREA ====================
Route::middleware(['auth', 'verified', 'role:guest'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->name('dashboard');

    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{booking}/payment', [BookingController::class, 'payment'])->name('booking.payment');
    Route::get('/booking/history', [BookingController::class, 'history'])->name('booking.history');
});

// ==================== ADMIN AREA ====================
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings/{booking}/confirm', [AdminBookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('/bookings/{booking}/check-in', [AdminBookingController::class, 'checkIn'])->name('bookings.checkin');
    Route::post('/bookings/{booking}/check-out', [AdminBookingController::class, 'checkOut'])->name('bookings.checkout');
});
