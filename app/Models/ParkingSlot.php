<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParkingSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'slot_code',
        'status',
        'rate_per_hour',
        'slot_type',
    ];

    public function parkingSessions()
    {
        return $this->hasMany(ParkingSession::class);
    }

    public function activeSession()
    {
        return $this->hasOne(ParkingSession::class)->whereNull('check_out');
    }
}