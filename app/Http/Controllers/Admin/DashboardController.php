<?php

namespace App\Http\Controllers;

use App\Models\Slot;
use App\Models\ParkingLog;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {
        $totalSlots = 50; 
        $availableSlots = 38; 
        $occupancyRate = 24;

        return view('admin.dashboard', compact('totalSlots', 'availableSlots', 'occupancyRate'));
    }
    // - Occupancy Overview
    public function occupancy()
    {
        $totalSlots = Slot::count();
        $occupied = Slot::where('is_occupied', true)->count();
        $available = $totalSlots - $occupied;

        return view('admin.occupancy', compact('totalSlots', 'occupied', 'available'));
    }

    public function slots()
    {
        $slots = Slot::all(); 
        return view('admin.slots', compact('slots'));
    }

    public function logs()
    {
        $logs = ParkingLog::with('user')->latest()->paginate(10);
        return view('admin.logs', compact('logs'));
    }

    public function users()
    {
        $users = User::where('role', '!=', 'admin')->get();
        return view('admin.users', compact('users'));
    }
}