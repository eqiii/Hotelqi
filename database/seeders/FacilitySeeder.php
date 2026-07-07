<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            ['name' => 'WiFi', 'description' => 'Internet cepat gratis'],
            ['name' => 'AC', 'description' => 'Pendingin ruangan'],
            ['name' => 'TV', 'description' => 'Smart TV'],
            ['name' => 'Breakfast', 'description' => 'Sarapan gratis'],
            ['name' => 'Mini Bar', 'description' => 'Minuman & snack'],
            ['name' => 'Pool', 'description' => 'Kolam renang'],
            ['name' => 'Gym', 'description' => 'Fitness center'],
        ];

        foreach ($facilities as $f) {
            Facility::create($f);
        }
    }
}
