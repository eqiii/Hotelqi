<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminHotelProfileController extends Controller
{
    public function edit()
    {
        $profile = HotelProfile::getProfile();
        return view('admin.hotel-profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'tagline'     => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'address'     => ['nullable', 'string'],
            'phone'       => ['nullable', 'string', 'max:50'],
            'email'       => ['nullable', 'email', 'max:255'],
            'website'     => ['nullable', 'url', 'max:255'],
            'logo'        => ['nullable', 'image', 'max:2048'],
        ]);

        $profile = HotelProfile::getProfile() ?? new HotelProfile();

        if ($request->hasFile('logo')) {
            if ($profile->logo) {
                Storage::disk('public')->delete($profile->logo);
            }
            $data['logo'] = $request->file('logo')->store('hotel', 'public');
        }

        $profile = HotelProfile::updateOrCreateProfile($data);

        return redirect()->route('admin.hotel-profile.edit')
            ->with('status', 'Profil hotel berhasil diperbarui.');
    }
}
