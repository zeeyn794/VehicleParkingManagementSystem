<?php

$logs = App\Models\ParkingLog::all();
foreach ($logs as $log) {
    if (!$log->vehicle) continue;
    
    $type = strtolower($log->vehicle->type ?? 'car');
    $rate = \App\Models\ParkingRate::where('vehicle_type', $type)->where('is_active', true)->first();
    $hourlyRate = $rate ? (float)$rate->hourly_rate : 50.00;
    
    if ($log->exit_time && $log->entry_time) {
        $actualDuration = $log->entry_time->diffInMinutes($log->exit_time);
        $actualHours = max(1, ceil(abs($actualDuration) / 60));
        $log->total_fee = $hourlyRate * $actualHours;
    } else {
        $log->total_fee = $hourlyRate * 1; 
    }
    
    $log->save();
    echo "Updated Log ID {$log->id} to total fee: {$log->total_fee} (Duration: {$actualDuration} mins, Hours: {$actualHours})\n";
}
