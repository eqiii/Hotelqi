<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\MidtransCallbackController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminGuestController;
use App\Http\Controllers\Admin\AdminRoomController;
use App\Http\Controllers\Admin\AdminRoomTypeController;
use App\Http\Controllers\Admin\AdminRestaurantMenuController;
use App\Http\Controllers\Admin\AdminHotelProfileController;
use App\Http\Controllers\Manager\ManagerDashboardController;
use App\Http\Controllers\Manager\ManagerFinanceController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// ==================== LANDING PAGE (PUBLIK) ====================
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/rooms', [LandingController::class, 'rooms'])->name('rooms');
Route::get('/rooms/{roomType}', [LandingController::class, 'roomDetail'])->name('rooms.detail');
Route::get('/faq', [LandingController::class, 'faq'])->name('faq');
Route::get('/restaurant', [RestaurantController::class, 'index'])->name('restaurant');

// ==================== AUTH ROUTES ====================
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('login.google.callback');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

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

        return redirect()->to(route('dashboard', [], false) . '?verified=1')
            ->with('success', '✅ Email berhasil diverifikasi! Selamat datang di ' . hotel_name());
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
});

// ==================== MIDTRANS CALLBACK (No auth, diakses Midtrans server) ====================
Route::post('/payment/callback', [MidtransCallbackController::class, 'handle'])->name('payment.callback');

// ==================== PAYMENT FINISH (Bisa diakses tanpa auth karena redirect dari Midtrans) ====================
Route::get('/payment/finish', [BookingController::class, 'finishPayment'])->name('payment.finish');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        return match($user?->role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'manager' => redirect()->route('manager.dashboard'),
            default   => redirect()->route('user.dashboard'),
        };
    })->name('dashboard');
});

// ==================== USER / GUEST AREA ====================
Route::middleware(['auth', 'verified', 'role:guest'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $guest = $user->guest;
        $recentBookings = $guest ? $guest->bookings()->with(['room.roomType', 'payment'])->latest()->take(3)->get() : collect();
        $totalBookings = $guest ? $guest->totalBookings() : 0;
        $totalSpent = $guest ? $guest->totalSpent() : 0;
        return view('user.dashboard', compact('recentBookings', 'totalBookings', 'totalSpent'));
    })->name('dashboard');

    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/create/{roomType}', [BookingController::class, 'create'])->name('booking.create');
    Route::get('/booking/{booking}/payment', [BookingController::class, 'payment'])->name('booking.payment');
    Route::get('/booking/history', [BookingController::class, 'history'])->name('booking.history');

    Route::post('/restaurant/order', [RestaurantController::class, 'store'])->name('restaurant.order.store');
    Route::get('/restaurant/orders', [RestaurantController::class, 'orders'])->name('restaurant.orders');
    Route::get('/restaurant/orders/{restaurantOrder}', [RestaurantController::class, 'show'])->name('restaurant.orders.show');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==================== ADMIN AREA ====================
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Bookings Management
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/confirm', [AdminBookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('/bookings/{booking}/check-in', [AdminBookingController::class, 'checkIn'])->name('bookings.checkin');
    Route::post('/bookings/{booking}/check-out', [AdminBookingController::class, 'checkOut'])->name('bookings.checkout');
    Route::post('/bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('bookings.cancel');

    // Guests Management
    Route::get('/guests', [AdminGuestController::class, 'index'])->name('guests.index');
    Route::get('/guests/{guest}', [AdminGuestController::class, 'show'])->name('guests.show');
    Route::get('/guests/{guest}/edit', [AdminGuestController::class, 'edit'])->name('guests.edit');
    Route::patch('/guests/{guest}', [AdminGuestController::class, 'update'])->name('guests.update');
    Route::delete('/guests/{guest}', [AdminGuestController::class, 'destroy'])->name('guests.destroy');

    // Rooms & Room Types
    Route::resource('room-types', AdminRoomTypeController::class)->except(['show']);
    Route::resource('rooms', AdminRoomController::class)->except(['show']);

    // Restaurant Menus
    Route::resource('restaurant-menus', AdminRestaurantMenuController::class)->except(['show']);

    // Hotel Profile
    Route::get('/hotel-profile', [AdminHotelProfileController::class, 'edit'])->name('hotel-profile.edit');
    Route::patch('/hotel-profile', [AdminHotelProfileController::class, 'update'])->name('hotel-profile.update');
});

// ==================== MANAGER AREA ====================
Route::middleware(['auth', 'verified', 'role:manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/finance', [ManagerFinanceController::class, 'index'])->name('finance');
    Route::get('/report/pdf', [ManagerFinanceController::class, 'downloadPdf'])->name('report.pdf');
});

