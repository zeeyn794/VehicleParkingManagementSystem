<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::where('email', 'test@example.com')->first();

        if ($user) {
            \App\Models\Vehicle::create([
                'user_id' => $user->id,
                'license_plate' => 'ABC-123',
                'make' => 'Toyota',
                'model' => 'Camry',
                'color' => 'Blue',
            ]);

            \App\Models\Vehicle::create([
                'user_id' => $user->id,
                'license_plate' => 'XYZ-789',
                'make' => 'Honda',
                'model' => 'Civic',
                'color' => 'Red',
            ]);

            \App\Models\Vehicle::create([
                'user_id' => $user->id,
                'license_plate' => 'DEF-456',
                'make' => 'Ford',
                'model' => 'F-150',
                'color' => 'Black',
            ]);
        }
    }
}
