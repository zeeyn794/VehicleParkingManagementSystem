<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParkingRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rates = [
            ['vehicle_type' => 'car', 'hourly_rate' => 2.50],
            ['vehicle_type' => 'motorcycle', 'hourly_rate' => 1.50],
            ['vehicle_type' => 'truck', 'hourly_rate' => 5.00],
            ['vehicle_type' => 'suv', 'hourly_rate' => 3.00],
            ['vehicle_type' => 'van', 'hourly_rate' => 3.50],
            ['vehicle_type' => 'electric', 'hourly_rate' => 2.00],
            ['vehicle_type' => 'hybrid', 'hourly_rate' => 2.25],
        ];

        foreach ($rates as $rate) {
            \App\Models\ParkingRate::create($rate);
        }
    }
}
