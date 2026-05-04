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
            ->get() : collect();

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

        $parkingRates = ParkingRate::where('is_active', true)->get();

        return view('dashboard', compact(
            'user',
            'activeSessions',
            'userVehicles',
            'availableSlots',
            'allParkingSlots',
            'parkingHistory',
            'totalSpent',
            'totalSessions',
            'parkingRates'
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

        if ($slot->status !== 'available') {
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
        
        $rate = \App\Models\ParkingRate::where('vehicle_type', $vehicle->type)
            ->where('is_active', true)
            ->first();
            
        $hourlyRate = $rate ? $rate->hourly_rate : 3.00; 
        $totalFee = $hourlyRate * $durationHours;

        $parkingLog = ParkingLog::create([
            'user_id'         => $user->id,
            'vehicle_id'      => $vehicle->id,
            'parking_slot_id' => $slot->id,
            'entry_time'      => $entryTime,
            'exit_time'       => $exitTime,
            'total_fee'       => $totalFee,
        ]);

        $slot->update(['status' => 'occupied']);

        return response()->json([
            'success' => true,
            'message' => "Vehicle parked successfully at {$slot->slot_number}!",
            'parking_session' => [
                'id' => $parkingLog->id,
                'slot_number' => $slot->slot_number,
                'vehicle' => $vehicle->license_plate,
                'entry_time' => $entryTime->format('H:i'),
                'exit_time' => $exitTime->format('H:i'),
                'total_fee' => $totalFee
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
        
        $rate = \App\Models\ParkingRate::where('vehicle_type', $session->vehicle->type)
            ->where('is_active', true)
            ->first();
            
        $hourlyRate = $rate ? $rate->hourly_rate : 3.00; 
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
        $actualHours = ceil($actualDuration / 60);
        $actualFee = $session->parkingSlot->hourly_rate * $actualHours;

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
            return [
                'id' => $slot->id,
                'slot_number' => $slot->slot_number,
                'location' => $slot->location,
                'status' => $slot->status,
                'type' => $slot->type ?? 'Standard',
                'hourly_rate' => $slot->hourly_rate
            ];
        });

        return response()->json([
            'success' => true,
            'slots' => $slots
        ]);
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
                return [
                    'id' => $record->id,
                    'date' => $record->created_at->format('Y-m-d'),
                    'slot' => $record->parkingSlot->slot_number,
                    'duration' => $record->entry_time->diffInHours($record->exit_time) . 'h ' . 
                               ($record->entry_time->diffInMinutes($record->exit_time) % 60) . 'm',
                    'vehicle' => $record->vehicle->license_plate,
                    'amount' => '₱' . number_format($record->total_fee, 2),
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
}
