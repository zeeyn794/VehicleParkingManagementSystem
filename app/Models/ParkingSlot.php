<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingSlot extends Model
{
    protected $fillable = [
        'slot_number',
        'status',
        'location',
        'hourly_rate',
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
    ];

    public function parkingLogs()
    {
        return $this->hasMany(ParkingLog::class);
    }

    public function isAvailable()
    {
        return $this->status === 'available';
    }
}
