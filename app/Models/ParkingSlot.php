<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParkingSlot extends Model
{
<<<<<<< HEAD
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
=======
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
>>>>>>> 9a6d934946a83daefc39d8387bab15770bd7d04a
