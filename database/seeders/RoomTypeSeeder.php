<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoomType;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Standard',
                'description' => 'Kamar nyaman untuk 1-2 orang',
                'base_price' => 300000,
                'max_guest' => 2,
                'total_bed' => 1,
            ],
            [
                'name' => 'Superior',
                'description' => 'Kamar lebih luas dengan fasilitas tambahan',
                'base_price' => 450000,
                'max_guest' => 2,
                'total_bed' => 1,
            ],
            [
                'name' => 'Deluxe',
                'description' => 'Kamar premium dengan view bagus',
                'base_price' => 650000,
                'max_guest' => 3,
                'total_bed' => 2,
            ],
            [
                'name' => 'Suite',
                'description' => 'Kamar mewah untuk keluarga',
                'base_price' => 1000000,
                'max_guest' => 4,
                'total_bed' => 2,
            ],
        ];

        foreach ($types as $t) {
            RoomType::create($t);
        }
    }
}
