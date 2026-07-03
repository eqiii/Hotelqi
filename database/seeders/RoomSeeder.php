<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\RoomType;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $roomConfigs = [
            'Superior' => [
                'prefix' => 'SUP',
                'total' => 12,
            ],
            'Deluxe' => [
                'prefix' => 'DLX',
                'total' => 10,
            ],
            'Executive' => [
                'prefix' => 'EXE',
                'total' => 8,
            ],
            'Suite' => [
                'prefix' => 'STE',
                'total' => 5,
            ],
        ];

        foreach ($roomConfigs as $typeName => $config) {

            $roomType = RoomType::where('name', $typeName)->first();

            if (!$roomType) {
                continue;
            }

            for ($i = 1; $i <= $config['total']; $i++) {

                Room::create([
                    'room_type_id' => $roomType->id,
                    'room_number' => $config['prefix'] . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'status' => 'available',
                ]);
            }
        }
    }
}
