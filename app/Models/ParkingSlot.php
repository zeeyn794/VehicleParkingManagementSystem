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
        'location',
        'status',
        'type',
        'hourly_rate',
    ];

    /**
     * 
     */
    public function parkingLogs()
    {
        return $this->hasMany(ParkingLog::class);
    }
}