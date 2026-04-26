<?php

namespace App\Http\Controllers\User; // Keeping your new folder structure

use App\Http\Controllers\Controller;
use App\Models\ParkingSlot;
use App\Models\ParkingSession; // Used in your version
use App\Models\ParkingLog;     // Used in groupmate's version
use App\Models\Vehicle;        // Used in groupmate's version
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // --- Data for your Dashboard Design ---
        $parkingSlots  = ParkingSlot::orderBy('slot_code')->get();
        $availableCount = $parkingSlots->where('status', 'available')->count();
        $totalSlots     = $parkingSlots->count();

        // This matches the variable your dashboard was screaming about
        $activeSessions = $user ? ParkingLog::whereIn('vehicle_id', $user->vehicles->pluck('id'))
            ->where('exit_time', '>', now())
            ->with('parkingSlot')
            ->get() : collect();

        $vehicles = $user->vehicles()->latest()->get();

        // Greeting based on time
        $hour = now()->hour;
        $greeting = match(true) {
            $hour < 12 => 'morning',
            $hour < 18 => 'afternoon',
            default    => 'evening',
        };

        // Return the dashboard INSIDE the user folder
        return view('user.dashboard', compact(
            'parkingSlots',
            'availableCount',
            'totalSlots',
            'activeSessions',
            'vehicles',
            'greeting'
        ));
    }

    // --- Keep the Park function from your groupmate ---
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

        return back()->with('success', "Vehicle parked successfully!");
    }
}