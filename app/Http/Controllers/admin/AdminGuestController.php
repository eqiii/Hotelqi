<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use Illuminate\Http\Request;

class AdminGuestController extends Controller
{
    public function index()
    {
        $guests = Guest::with('user')->latest()->paginate(15);

        return view('admin.guests.index', compact('guests'));
    }

    public function show(Guest $guest)
    {
        $guest->load(['user', 'bookings.room.roomType', 'restaurantOrders']);

        return view('admin.guests.show', compact('guest'));
    }
}
