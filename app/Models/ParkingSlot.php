<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'slot_code',
        'slot_number',
        'status',
        'type',
        'hourly_rate',
    ];

    /**
     * Relationship with ParkingLogs or Sessions
     */
    public function parkingLogs()
    {
        return $this->hasMany(ParkingLog::class);
    }
}