<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminGuestController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $guests = Guest::with('user')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('phone', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15);

        return view('admin.guests.index', compact('guests', 'search'));
    }

    public function show(Guest $guest)
    {
        $guest->load(['user', 'bookings.room.roomType', 'bookings.payment']);
        return view('admin.guests.show', compact('guest'));
    }

    public function edit(Guest $guest)
    {
        $guest->load('user');
        return view('admin.guests.edit', compact('guest'));
    }

    public function update(Request $request, Guest $guest)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'unique:users,email,' . $guest->user_id],
            'phone'      => ['nullable', 'string', 'max:20'],
            'address'    => ['nullable', 'string'],
            'ktp_number' => ['nullable', 'string', 'max:20'],
        ]);

        $guest->user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
        ]);

        $guest->update([
            'full_name'  => $data['name'],
            'phone'      => $data['phone'],
            'address'    => $data['address'],
            'ktp_number' => $data['ktp_number'],
        ]);

        return redirect()->route('admin.guests.show', $guest)
            ->with('status', 'Data tamu berhasil diperbarui.');
    }

    public function destroy(Guest $guest)
    {
        $user = $guest->user;
        $guest->delete();
        $user->delete();

        return redirect()->route('admin.guests.index')
            ->with('status', 'Data tamu berhasil dihapus.');
    }
}
