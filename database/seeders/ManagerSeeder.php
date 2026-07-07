<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'manager@gmail.com'],
            [
                'name'              => 'Hotel Manager',
                'password'          => Hash::make('password'),
                'role'              => 'manager',
                'email_verified_at' => now(),
            ]
        );
    }
}
