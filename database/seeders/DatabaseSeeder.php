<?php

namespace Database\Seeders;

use App\Models\Guest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ==========================
        // Admin Account
        // ==========================
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // ==========================
        // Guest Account
        // ==========================
        $guestUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'guest',
                'email_verified_at' => now(),
            ]
        );

        Guest::firstOrCreate(
            ['user_id' => $guestUser->id],
            [
                'phone' => '081234567890',
            ]
        );

        // ==========================
        // Hotel Data
        // ==========================
        $this->call([
            FacilitySeeder::class,
            RoomTypeSeeder::class,
            RoomSeeder::class,
            // HotelProfileSeeder::class,
            // RestaurantMenuSeeder::class,
        ]);
    }
}
