<?php

use App\Models\HotelProfile;
use Carbon\Carbon;

if (!function_exists('format_rupiah')) {
    function format_rupiah(float $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('format_date')) {
    function format_date($date, string $format = 'd M Y'): string
    {
        return Carbon::parse($date)->translatedFormat($format);
    }
}

if (!function_exists('format_datetime')) {
    function format_datetime($date, string $format = 'd M Y H:i'): string
    {
        return Carbon::parse($date)->translatedFormat($format);
    }
}

if (!function_exists('hotel_name')) {
    function hotel_name(): string
    {
        $profile = HotelProfile::getProfile();
        return $profile ? $profile->name : config('app.name');
    }
}
