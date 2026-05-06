<?php

$logs = App\Models\ParkingLog::all();
foreach ($logs as $log) {
    echo "ID: {$log->id} | Entry: {$log->entry_time} | Exit: {$log->exit_time} | Fee: {$log->total_fee}\n";
}
