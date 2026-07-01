<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\HotelProfile;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Share hotel profile ke semua view (untuk footer, header, dll)
        View::composer('*', function ($view) {
            $view->with('hotelProfile', HotelProfile::getProfile());
        });
    }
}
    