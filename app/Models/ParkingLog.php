<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingLog extends Model
{
    protected $fillable = [
        'vehicle_id',
        'parking_slot_id',
        'entry_time',
        'exit_time',
        'total_fee',
    ];

    protected $casts = [
        'entry_time' => 'datetime',
        'exit_time' => 'datetime',
        'total_fee' => 'decimal:2',
    ];

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
