<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ParkingLog extends Model
{
    protected $fillable = [
        'user_id',
        'vehicle_id',
        'parking_slot_id',
        'entry_time',
        'exit_time',
        'total_fee',
        'payment_method',
    ];

    protected $casts = [
        'entry_time' => 'datetime',
        'exit_time' => 'datetime',
        'total_fee' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function parkingSlot()
    {
        return $this->belongsTo(ParkingSlot::class);
    }

    public function calculateFee()
    {
        if ($this->exit_time && $this->entry_time) {
            $hours = $this->entry_time->diffInHours($this->exit_time, true);
            return $hours * $this->parkingSlot->hourly_rate;
        }
        return 0;
    }
}
