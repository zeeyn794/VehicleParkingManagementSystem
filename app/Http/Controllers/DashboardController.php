<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ParkingSlot;
use App\Models\ParkingSession;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
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