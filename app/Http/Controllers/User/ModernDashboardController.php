<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ParkingLog;
use App\Models\ParkingSlot;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\ParkingRate;

class ModernDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $activeSessions = $user ? ParkingLog::whereIn('vehicle_id', $user->vehicles->pluck('id'))
            ->where('exit_time', '>', now())
            ->with('parkingSlot', 'vehicle')
            ->get()
            ->map(function($session) {
                $type = strtolower($session->vehicle->type ?? 'car');
                $rate = \App\Models\ParkingRate::where('vehicle_type', $type)
                    ->where('is_active', true)
                    ->first();
                $session->hourly_rate = $rate ? (float)$rate->hourly_rate : (float)($session->parkingSlot->hourly_rate ?? 50.00);
                return $session;
            }) : collect();

        $userVehicles = $user ? $user->vehicles()->latest()->get() : collect();

        $allParkingSlots = ParkingSlot::all()->map(function($slot) {
            $activeLog = ParkingLog::where('parking_slot_id', $slot->id)
                ->where('exit_time', '>', now())
                ->with('vehicle')
                ->first();

            if ($activeLog) {
                $slot->status = 'occupied';
                $slot->current_user = $activeLog->vehicle->user_id ?? null;
                $slot->exit_time = $activeLog->exit_time;
            } else {
                $slot->status = 'available';
                $slot->current_user = null;
                $slot->exit_time = null;
            }

            return $slot;
        });

        $availableSlots = $allParkingSlots->where('status', 'available');

        $parkingHistory = collect();

        $totalSpent = $user ? ParkingLog::whereIn('vehicle_id', $user->vehicles->pluck('id'))
            ->sum('total_fee') : 0;

        $totalSessions = $user ? ParkingLog::whereIn('vehicle_id', $user->vehicles->pluck('id'))
            ->count() : 0;

        $parkingRates = ParkingRate::where('is_active', true)->get()->mapWithKeys(function($item) {
            return [strtolower($item->vehicle_type) => $item->hourly_rate];
        });
        
        $recentNotifications = $this->getLatestNotifications(5);
        $allNotificationsCount = $recentNotifications->count(); // In a real app, this would be unread count

        return view('dashboard', compact(
            'user',
            'activeSessions',
            'userVehicles',
            'availableSlots',
            'allParkingSlots',
            'parkingHistory',
            'totalSpent',
            'totalSessions',
            'parkingRates',
            'recentNotifications',
            'allNotificationsCount'
        ));
    }

    public function bookParking(Request $request)
    {
        $request->validate([
            'slot_id' => 'required|exists:parking_slots,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'duration_hours' => 'required|integer|min:1|max:24',
            'payment_method' => 'required|string|in:card,wallet,cash',
        ]);

        $slot = ParkingSlot::findOrFail($request->slot_id);

        $isOccupied = ParkingLog::where('parking_slot_id', $slot->id)
            ->where('exit_time', '>', now())
            ->exists();

        if ($isOccupied) {
            return response()->json([
                'success' => false,
                'message' => 'Slot is not available.'
            ], 400);
        }

        $user = auth()->user();
        $vehicle = Vehicle::where('id', $request->vehicle_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $entryTime = now();
        $durationHours = (int) $request->duration_hours;
        $exitTime = $entryTime->copy()->addHours($durationHours);
        
        $type = strtolower($vehicle->type ?? 'car');
        $rate = \App\Models\ParkingRate::where('vehicle_type', $type)
            ->where('is_active', true)
            ->first();
            
        $hourlyRate = $rate ? (float)$rate->hourly_rate : (float)($slot->hourly_rate ?? 50.00); 
        $totalFee = $hourlyRate * $durationHours;

        $parkingLog = ParkingLog::create([
            'user_id'         => $user->id,
            'vehicle_id'      => $vehicle->id,
            'parking_slot_id' => $slot->id,
            'entry_time'      => $entryTime,
            'exit_time'       => $exitTime,
            'total_fee'       => $totalFee,
            'payment_method'  => $request->payment_method ?? 'cash',
        ]);

        $slot->update(['status' => 'occupied']);

        return response()->json([
            'success' => true,
            'message' => "Vehicle parked successfully at {$slot->slot_number}!",
            'parking_session' => [
                'id' => $parkingLog->id,
                'slot_number' => $slot->slot_number,
                'vehicle_id' => $vehicle->id,
                'vehicle_type' => $vehicle->type,
                'license_plate' => $vehicle->license_plate,
                'vehicle' => $vehicle->license_plate,
                'entry_time' => $entryTime->toIso8601String(),
                'exit_time' => $exitTime->toIso8601String(),
                'total_fee' => $totalFee,
                'hourly_rate' => $rate ? (float)$rate->hourly_rate : 50.00
            ]
        ]);
    }

    public function extendParking(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:parking_logs,id',
            'additional_hours' => 'required|integer|min:1|max:12',
        ]);

        $session = ParkingLog::findOrFail($request->session_id);
        $user = auth()->user();

        if (!$user->vehicles->pluck('id')->contains($session->vehicle_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to parking session.'
            ], 403);
        }

        $additionalHours = (int) $request->additional_hours;
        
        $rate = \App\Models\ParkingRate::where('vehicle_type', $session->vehicle->type ?? 'car')
            ->where('is_active', true)
            ->first()
            ?? \App\Models\ParkingRate::where('is_active', true)->first();
            
        $hourlyRate = $rate ? $rate->hourly_rate : 50.00;
        $additionalFee = $hourlyRate * $additionalHours;

        $session->exit_time = $session->exit_time->addHours($additionalHours);
        $session->total_fee += $additionalFee;
        $session->save();

        return response()->json([
            'success' => true,
            'message' => "Parking time extended by {$additionalHours} hours!",
            'new_exit_time' => $session->exit_time->format('H:i'),
            'additional_fee' => $additionalFee,
            'new_total_fee' => $session->total_fee
        ]);
    }

    public function endParking(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:parking_logs,id',
        ]);

        $session = ParkingLog::findOrFail($request->session_id);
        $user = auth()->user();

        if (!$user->vehicles->pluck('id')->contains($session->vehicle_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to parking session.'
            ], 403);
        }

        $actualDuration = now()->diffInMinutes($session->entry_time);
        $actualHours = max(1, ceil($actualDuration / 60));
        
        $rate = \App\Models\ParkingRate::where('vehicle_type', $session->vehicle->type ?? 'car')
            ->where('is_active', true)
            ->first()
            ?? \App\Models\ParkingRate::where('is_active', true)->first();
        $hourlyRate = $rate ? $rate->hourly_rate : 50.00;
        $actualFee = $hourlyRate * $actualHours;

        $session->exit_time = now();
        $session->total_fee = $actualFee;
        $session->save();

        $session->parkingSlot->update(['status' => 'available']);

        return response()->json([
            'success' => true,
            'message' => 'Parking session ended successfully!',
            'final_fee' => $actualFee,
            'duration' => $actualDuration
        ]);
    }

    public function getParkingSlots()
    {
        $slots = ParkingSlot::all()->map(function ($slot) {
            $isOccupied = ParkingLog::where('parking_slot_id', $slot->id)
                ->where('exit_time', '>', now())
                ->exists();

            return [
                'id'          => $slot->id,
                'slot_number' => $slot->slot_number,
                'location'    => $slot->location,
                'status'      => $isOccupied ? 'occupied' : 'available',
                'type'        => $slot->type ?? 'Standard',
            ];
        });

        return response()->json([
            'success' => true,
            'slots' => $slots
        ]);
    }

    public function exportHistory(Request $request)
    {
        $user = auth()->user();
        
        $query = ParkingLog::whereIn('vehicle_id', $user->vehicles->pluck('id'))
            ->with(['parkingSlot', 'vehicle']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('vehicle', function ($subQuery) use ($search) {
                    $subQuery->where('license_plate', 'like', "%{$search}%");
                })->orWhereHas('parkingSlot', function ($subQuery) use ($search) {
                    $subQuery->where('slot_number', 'like', "%{$search}%");
                });
            });
        }

        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'today': $query->whereDate('created_at', today()); break;
                case 'week': $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]); break;
                case 'month': $query->whereMonth('created_at', now()->month); break;
            }
        }

        $logs = $query->orderBy('created_at', 'desc')->get();
        $filename = "my_parking_history_" . date('Y-m-d') . ".csv";
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($logs) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['Date', 'Slot', 'Vehicle', 'Type', 'Entry Time', 'Exit Time', 'Duration', 'Fee', 'Method', 'Status']);

            foreach ($logs as $log) {
                $totalMinutes = $log->entry_time->diffInMinutes($log->exit_time);
                $duration = floor($totalMinutes / 60) . 'h ' . ($totalMinutes % 60) . 'm';
                
                fputcsv($file, [
                    $log->created_at->format('Y-m-d'),
                    $log->parkingSlot->slot_number ?? 'N/A',
                    $log->vehicle->license_plate ?? 'N/A',
                    ucfirst($log->vehicle->type ?? 'Car'),
                    $log->entry_time->format('H:i:s'),
                    $log->exit_time->format('H:i:s'),
                    $duration,
                    '₱' . number_format($log->total_fee, 2),
                    ucfirst($log->payment_method ?? 'Cash'),
                    $log->exit_time > now() ? 'Active' : 'Completed'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function getParkingHistory(Request $request)
    {
        $user = auth()->user();
        
        $query = ParkingLog::whereIn('vehicle_id', $user->vehicles->pluck('id'))
            ->with('parkingSlot', 'vehicle');

        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month);
                    break;
            }
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('vehicle', function ($subQuery) use ($search) {
                    $subQuery->where('license_plate', 'like', "%{$search}%");
                })->orWhereHas('parkingSlot', function ($subQuery) use ($search) {
                    $subQuery->where('slot_number', 'like', "%{$search}%");
                });
            });
        }

        $history = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'history' => $history->map(function ($record) {
                $totalMinutes = $record->entry_time->diffInMinutes($record->exit_time);
                $duration = floor($totalMinutes / 60) . 'h ' . ($totalMinutes % 60) . 'm';
                
                return [
                    'id' => $record->id,
                    'date' => $record->entry_time->format('M d, Y'),
                    'slot' => $record->parkingSlot->slot_number ?? 'N/A',
                    'duration' => $duration,
                    'vehicle' => $record->vehicle->license_plate ?? 'N/A',
                    'vehicle_details' => ($record->vehicle->make ?? '') . ' ' . ($record->vehicle->model ?? ''),
                    'vehicle_type' => ucfirst($record->vehicle->type ?? 'Car'),
                    'entry_time' => $record->entry_time->format('h:i:s A'),
                    'exit_time' => $record->exit_time ? $record->exit_time->format('h:i:s A') : '—',
                    'amount' => '₱' . number_format($record->total_fee, 2),
                    'method' => ucfirst($record->payment_method ?? 'Cash'),
                    'status' => $record->exit_time > now() ? 'active' : 'completed'
                ];
            })
        ]);
    }

    public function addVehicle(Request $request)
    {
        $request->validate([
            'type' => 'required|string|exists:parking_rates,vehicle_type',
            'license_plate' => 'required|string|unique:vehicles,license_plate',
            'make' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();

        $vehicle = $user->vehicles()->create([
            'type' => $request->type,
            'license_plate' => $request->license_plate,
            'make' => $request->make,
            'model' => $request->model,
            'color' => $request->color,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Vehicle added successfully!',
            'vehicle' => $vehicle
        ]);
    }

    public function addPaymentMethod(Request $request)
    {
        $request->validate([
            'payment_type' => 'required|string|in:card,ewallet,bank',
            'payment_data' => 'required|string',
            'is_primary' => 'nullable|boolean',
        ]);

        $user = auth()->user();
        $paymentData = json_decode($request->payment_data, true);


        return response()->json([
            'success' => true,
            'message' => 'Payment method added successfully!',
            'payment_type' => $request->payment_type,
            'is_primary' => $request->boolean('is_primary', false)
        ]);
    }

    public function historyPage(Request $request)
    {
        $user = auth()->user();

        $query = ParkingLog::whereIn('vehicle_id', $user->vehicles->pluck('id'))
            ->with(['parkingSlot', 'vehicle'])
            ->latest('entry_time');

        if ($request->filter && $request->filter !== 'all') {
            switch ($request->filter) {
                case 'today':
                    $query->whereDate('entry_time', today());
                    break;
                case 'week':
                    $query->whereBetween('entry_time', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('entry_time', now()->month)
                          ->whereYear('entry_time', now()->year);
                    break;
            }
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('vehicle', fn($s) => $s->where('license_plate', 'like', "%$search%"))
                  ->orWhereHas('parkingSlot', fn($s) => $s->where('slot_number', 'like', "%$search%"));
            });
        }

        $transactions = $query->paginate(15)->withQueryString();
        $totalSpent    = ParkingLog::whereIn('vehicle_id', $user->vehicles->pluck('id'))->sum('total_fee');
        $totalSessions = ParkingLog::whereIn('vehicle_id', $user->vehicles->pluck('id'))->count();

        return view('user.history', compact('transactions', 'totalSpent', 'totalSessions'));
    }

    private function getLatestNotifications($limit = 5)
    {
        $user = auth()->user();
        if (!$user) return collect();
        
        $notifs = collect();

        $notifs->push([
            'type' => 'system',
            'title' => 'Welcome to ParkMaster!',
            'message' => "Hi {$user->name}, thank you for joining our parking management system.",
            'icon' => 'fas fa-hand-peace',
            'bg' => 'rgba(34, 211, 238, 0.1)',
            'color' => 'var(--secondary-color)',
            'time' => $user->created_at,
        ]);

        $logs = ParkingLog::where('user_id', $user->id)
            ->with(['parkingSlot', 'vehicle'])
            ->latest()
            ->limit($limit)
            ->get();

        foreach ($logs as $log) {
            $isCompleted = $log->exit_time <= now();
            $notifs->push([
                'type' => 'parking',
                'title' => $isCompleted ? 'Parking Session Completed' : 'Active Parking Session',
                'message' => $isCompleted 
                    ? "Your session at Slot {$log->parkingSlot->slot_number} for {$log->vehicle->license_plate} has ended."
                    : "You currently have an active session at Slot {$log->parkingSlot->slot_number}.",
                'icon' => 'fas fa-parking',
                'bg' => $isCompleted ? 'rgba(16, 185, 129, 0.1)' : 'rgba(245, 48, 3, 0.1)',
                'color' => $isCompleted ? 'var(--success-color)' : 'var(--primary-color)',
                'time' => $log->created_at,
            ]);
        }

        $vehicles = $user->vehicles()->latest()->limit($limit)->get();
        foreach ($vehicles as $vehicle) {
            $notifs->push([
                'type' => 'vehicle',
                'title' => 'New Vehicle Added',
                'message' => "Vehicle {$vehicle->license_plate} ({$vehicle->make} {$vehicle->model}) has been registered.",
                'icon' => 'fas fa-car-side',
                'bg' => 'rgba(245, 158, 11, 0.1)',
                'color' => 'var(--warning-color)',
                'time' => $vehicle->created_at,
            ]);
        }

        return $notifs->sortByDesc('time')->take($limit);
    }
}
