<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingRate extends Model
{
    protected $fillable = [
        'vehicle_type',
        'hourly_rate',
        'is_active'
    ];
}
