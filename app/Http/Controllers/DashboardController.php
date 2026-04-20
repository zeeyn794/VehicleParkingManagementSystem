<?php

<<<<<<< HEAD
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ParkingSlot;
use App\Models\ParkingSession;
use Illuminate\Support\Facades\Auth;
=======
namespace App\Http\Controllers;

use App\Models\ParkingSlot;
use App\Models\ParkingLog;
use App\Models\Vehicle;
use Illuminate\Http\Request;
>>>>>>> 9a6d934946a83daefc39d8387bab15770bd7d04a

class DashboardController extends Controller
{
    public function index()
    {
<<<<<<< HEAD
        $user = Auth::user();

        // Parking availability — simple query
        $parkingSlots  = ParkingSlot::orderBy('slot_code')->get();
        $availableCount = $parkingSlots->where('status', 'available')->count();
        $totalSlots     = $parkingSlots->count();

        // Active session — fetch current record for this user
        $activeSession = ParkingSession::with(['vehicle', 'parkingSlot'])
            ->where('user_id', $user->id)
            ->whereNull('check_out')
            ->first();

        // Vehicles — basic CRUD collection
        $vehicles = $user->vehicles()->latest()->get();

        // History — indexed query on past completed records (limit to 4 for dashboard preview)
        $recentHistory = ParkingSession::with(['vehicle', 'parkingSlot'])
            ->where('user_id', $user->id)
            ->whereNotNull('check_out')
            ->latest('check_out')
            ->limit(4)
            ->get();

        // Monthly trip count
        $monthTrips = ParkingSession::where('user_id', $user->id)
            ->whereMonth('check_in', now()->month)
            ->whereYear('check_in', now()->year)
            ->count();

        // Greeting based on time
        $hour = now()->hour;
        $greeting = match(true) {
            $hour < 12 => 'morning',
            $hour < 18 => 'afternoon',
            default    => 'evening',
        };

        return view('user.dashboard', compact(
            'parkingSlots',
            'availableCount',
            'totalSlots',
            'activeSession',
            'vehicles',
            'recentHistory',
            'monthTrips',
            'greeting',
        ));
    }
}
=======
        $availableSlots = ParkingSlot::where('status', 'available')->get();
        $user = auth()->user();
        $userVehicles = $user ? $user->vehicles : collect();
        $activeSessions = $user ? ParkingLog::whereIn('vehicle_id', $userVehicles->pluck('id'))
            ->where('exit_time', '>', now())
            ->with('parkingSlot')
            ->get() : collect();

        return view('dashboard', compact(
            'availableSlots',
            'userVehicles',
            'activeSessions'
        ));
    }

    public function park(Request $request)
    {
        $request->validate([
            'slot_id' => 'required|exists:parking_slots,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'duration_hours' => 'required|integer|min:1|max:24',
            'terms' => 'required|accepted',
        ]);

        $slot = ParkingSlot::findOrFail($request->slot_id);

        if ($slot->status !== 'available') {
            return back()->with('error', 'Slot is not available.');
        }

        $user = auth()->user();
        if (!$user) {
            return back()->with('error', 'User not authenticated.');
        }

        $vehicle = Vehicle::where('id', $request->vehicle_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Check if vehicle is already parked
        $existingParking = ParkingLog::where('vehicle_id', $vehicle->id)
            ->whereNull('exit_time')
            ->first();

        if ($existingParking) {
            return back()->with('error', 'This vehicle is already parked in another slot.');
        }

        $entryTime = now();
        $exitTime = $entryTime->copy()->addHours($request->duration_hours);
        $totalFee = $slot->hourly_rate * $request->duration_hours;

        // Create parking log
        ParkingLog::create([
            'vehicle_id' => $vehicle->id,
            'parking_slot_id' => $slot->id,
            'entry_time' => $entryTime,
            'exit_time' => $exitTime,
            'total_fee' => $totalFee,
        ]);

        // Update slot status
        $slot->update(['status' => 'occupied']);

        return back()->with('success', "Vehicle parked successfully! Slot: {$slot->slot_number}, Duration: {$request->duration_hours} hours, Total Fee: \${$totalFee}");
    }
}
>>>>>>> 9a6d934946a83daefc39d8387bab15770bd7d04a
