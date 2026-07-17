<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\RoomType;
use App\Models\Testimonial;
use App\Models\Gallery;
use App\Models\Faq;
use App\Models\HotelProfile;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $hotelProfile = HotelProfile::getProfile();
        $roomTypes = RoomType::with('facilities')->take(3)->get();
        $testimonials = Testimonial::approved()->take(3)->get();
        $galleries = Gallery::take(8)->get();
        $facilities = Facility::active()->ordered()->get();

        return view('landing.index', compact('hotelProfile', 'roomTypes', 'testimonials', 'galleries', 'facilities'));
    }

    public function rooms()
    {
        $roomTypes = RoomType::with('facilities')->paginate(6);
        return view('landing.rooms', compact('roomTypes'));
    }

    public function roomDetail(RoomType $roomType)
    {
        $roomType->load('facilities');
        return view('landing.room-detail', compact('roomType'));
    }

    public function faq()
    {
        $faqs = Faq::ordered()->get();
        return view('landing.faq', compact('faqs'));
    }
}
