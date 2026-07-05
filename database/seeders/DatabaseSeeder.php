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
        // Admin Account (Primary)
        // ==========================
        User::firstOrCreate(
            ['email' => 'admin@hoteleqi.com'],
            [
                'name'              => 'Administrator',
                'password'          => Hash::make('Admin@12345'),
                'role'              => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Admin fallback (lama) - update password jika ada
        $oldAdmin = User::where('email', 'admin@gmail.com')->first();
        if ($oldAdmin) {
            $oldAdmin->update([
                'password'          => Hash::make('Admin@12345'),
                'email_verified_at' => now(),
            ]);
        }

        // ==========================
        // Guest Account (untuk testing)
        // ==========================
        $guestUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name'              => 'Test User',
                'password'          => Hash::make('password'),
                'role'              => 'guest',
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
