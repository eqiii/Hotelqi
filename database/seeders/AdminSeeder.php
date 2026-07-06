<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek dulu biar tidak double admin
        $admin = User::where('email', 'admin@hotel.com')->first();

        if (!$admin) {
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@hotel.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(), // optional biar langsung verified
            ]);
        }
    }
}
