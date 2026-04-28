<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ParkingLog;
use App\Models\ParkingSlot;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModernDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get active parking sessions for this user
        $activeSessions = $user ? ParkingLog::whereIn('vehicle_id', $user->vehicles->pluck('id'))
            ->where('exit_time', '>', now())
            ->with('parkingSlot', 'vehicle')
            ->get() : collect();

        // Get user's vehicles
        $userVehicles = $user ? $user->vehicles()->latest()->get() : collect();

        // Get ALL parking slots with real-time occupancy status
        $allParkingSlots = ParkingSlot::all()->map(function($slot) {
            // Check if slot has an active session (exit_time in future means active)
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

        // Get available slots for booking
        $availableSlots = $allParkingSlots->where('status', 'available');

        // Get parking history - initially empty, will be loaded via search
        $parkingHistory = collect();

        // Calculate user stats
        $totalSpent = $user ? ParkingLog::whereIn('vehicle_id', $user->vehicles->pluck('id'))
            ->sum('total_fee') : 0;

        $totalSessions = $user ? ParkingLog::whereIn('vehicle_id', $user->vehicles->pluck('id'))
            ->count() : 0;

        // Return the dashboard view
        return view('dashboard', compact(
            'user',
            'activeSessions',
            'userVehicles',
            'availableSlots',
            'allParkingSlots',
            'parkingHistory',
            'totalSpent',
            'totalSessions'
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
        $exitTime = $entryTime->copy()->addHours($request->duration_hours);
        $totalFee = $slot->hourly_rate * $request->duration_hours;

        $parkingLog = ParkingLog::create([
            'vehicle_id' => $vehicle->id,
            'parking_slot_id' => $slot->id,
            'entry_time' => $entryTime,
            'exit_time' => $exitTime,
            'total_fee' => $totalFee,
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

        // Verify this session belongs to the user
        if (!$user->vehicles->pluck('id')->contains($session->vehicle_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to parking session.'
            ], 403);
        }

        $additionalHours = $request->additional_hours;
        $slot = $session->parkingSlot;
        $additionalFee = $slot->hourly_rate * $additionalHours;

        // Extend the exit time
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

        // Verify this session belongs to the user
        if (!$user->vehicles->pluck('id')->contains($session->vehicle_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to parking session.'
            ], 403);
        }

        // Calculate actual fee based on current time
        $actualDuration = now()->diffInMinutes($session->entry_time);
        $actualHours = ceil($actualDuration / 60);
        $actualFee = $session->parkingSlot->hourly_rate * $actualHours;

        // Update session
        $session->exit_time = now();
        $session->total_fee = $actualFee;
        $session->save();

        // Free up the slot
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

        // Apply date filter
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

        // Apply search
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
                    'amount' => '$' . number_format($record->total_fee, 2),
                    'status' => $record->exit_time > now() ? 'active' : 'completed'
                ];
            })
        ]);
    }

    public function addVehicle(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:car,motorcycle,truck,suv,van,electric,hybrid',
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

        // In a real app, you would save this to a payment_methods table
        // For now, we'll just return success since this is a demo

        return response()->json([
            'success' => true,
            'message' => 'Payment method added successfully!',
            'payment_type' => $request->payment_type,
            'is_primary' => $request->boolean('is_primary', false)
        ]);
    }
}
