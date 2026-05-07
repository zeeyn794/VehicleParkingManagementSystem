<?php

namespace App\Http\Controllers\User; 
use App\Http\Controllers\Controller;
use App\Models\ParkingSlot;
use App\Models\ParkingSession; 
use App\Models\ParkingLog;     
use App\Models\Vehicle;        
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $activeSessions = $user ? ParkingLog::whereIn('vehicle_id', $user->vehicles->pluck('id'))
            ->where('exit_time', '>', now())
            ->with('parkingSlot')
            ->get() : collect();

        $userVehicles = $user ? $user->vehicles()->latest()->get() : collect();

        $availableSlots = ParkingSlot::where('status', 'available')->get();

        return view('dashboard', compact(
            'activeSessions',
            'userVehicles',
            'availableSlots'
        ));
    }

    public function park(Request $request)
    {
        $request->validate([
            'slot_id' => 'required|exists:parking_slots,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'duration_hours' => 'required|integer|min:1|max:24',
            'terms' => 'required|accepted',
            'payment_method' => 'required|string|in:card,wallet,upi',
        ]);

        $slot = ParkingSlot::findOrFail($request->slot_id);

        if ($slot->status !== 'available') {
            return back()->with('error', 'Slot is not available.');
        }

        $user = auth()->user();
        $vehicle = Vehicle::where('id', $request->vehicle_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $entryTime = now();
        $exitTime = $entryTime->copy()->addHours($request->duration_hours);
        $totalFee = $slot->hourly_rate * $request->duration_hours;

        ParkingLog::create([
            'vehicle_id' => $vehicle->id,
            'parking_slot_id' => $slot->id,
            'entry_time' => $entryTime,
            'exit_time' => $exitTime,
            'total_fee' => $totalFee,
        ]);

        $slot->update(['status' => 'occupied']);

        return back()->with('success', "Vehicle parked successfully at {$slot->slot_number}!");
    }
}