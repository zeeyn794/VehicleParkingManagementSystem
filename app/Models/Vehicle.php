<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'license_plate',
        'user_id',
        'make',
        'model',
        'color',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parkingLogs()
    {
        return $this->hasMany(ParkingLog::class);
    }
}
