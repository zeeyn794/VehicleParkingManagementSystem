<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParkingSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\ParkingSlot::create([
            'slot_number' => 'A01',
            'status' => 'available',
            'location' => 'Ground Floor',
            'hourly_rate' => 2.50,
        ]);

        \App\Models\ParkingSlot::create([
            'slot_number' => 'A02',
            'status' => 'available',
            'location' => 'Ground Floor',
            'hourly_rate' => 2.50,
        ]);

        \App\Models\ParkingSlot::create([
            'slot_number' => 'A03',
            'status' => 'occupied',
            'location' => 'Ground Floor',
            'hourly_rate' => 2.50,
        ]);

        \App\Models\ParkingSlot::create([
            'slot_number' => 'B01',
            'status' => 'available',
            'location' => 'First Floor',
            'hourly_rate' => 3.00,
        ]);

        \App\Models\ParkingSlot::create([
            'slot_number' => 'B02',
            'status' => 'available',
            'location' => 'First Floor',
            'hourly_rate' => 3.00,
        ]);

        \App\Models\ParkingSlot::create([
            'slot_number' => 'B03',
            'status' => 'available',
            'location' => 'First Floor',
            'hourly_rate' => 3.00,
        ]);

        \App\Models\ParkingSlot::create([
            'slot_number' => 'C01',
            'status' => 'occupied',
            'location' => 'Second Floor',
            'hourly_rate' => 3.50,
        ]);

        \App\Models\ParkingSlot::create([
            'slot_number' => 'C02',
            'status' => 'available',
            'location' => 'Second Floor',
            'hourly_rate' => 3.50,
        ]);

        \App\Models\ParkingSlot::create([
            'slot_number' => 'C03',
            'status' => 'available',
            'location' => 'Second Floor',
            'hourly_rate' => 3.50,
        ]);

        \App\Models\ParkingSlot::create([
            'slot_number' => 'C04',
            'status' => 'available',
            'location' => 'Second Floor',
            'hourly_rate' => 3.50,
        ]);
    }
}
