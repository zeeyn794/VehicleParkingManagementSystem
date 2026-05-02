<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParkingSlot;
use App\Models\ParkingLog;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {
        $totalSlots = ParkingSlot::count();
        $occupied = ParkingSlot::where('status', 'occupied')->count();
        $available = $totalSlots - $occupied;
        $totalUsers = User::where('role', '!=', 'admin')->count();

        return view('admin.dashboard', compact('totalSlots', 'occupied', 'available', 'totalUsers'));
    }
    public function occupancy()
    {
        $totalSlots = ParkingSlot::count();
        $occupied = ParkingSlot::where('status', 'occupied')->count();
        $available = $totalSlots - $occupied;

        return view('admin.occupancy', compact('totalSlots', 'occupied', 'available'));
    }

    public function slots()
    {
        $slots = ParkingSlot::all(); 
        return view('admin.slots', compact('slots'));
    }

    public function logs(Request $request)
    {
        $query = ParkingLog::with(['user', 'parkingSlot', 'vehicle']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($sq) use ($search) {
                    $sq->where('name', 'like', "%$search%");
                })->orWhereHas('parkingSlot', function($sq) use ($search) {
                    $sq->where('slot_number', 'like', "%$search%");
                })->orWhereHas('vehicle', function($sq) use ($search) {
                    $sq->where('license_plate', 'like', "%$search%");
                });
            });
        }

        $logs = $query->latest()->paginate(10)->withQueryString();
        return view('admin.logs', compact('logs'));
    }

    public function users()
    {
        $users = User::where('role', '!=', 'admin')->get();
        return view('admin.users', compact('users'));
    }

    public function storeSlot(Request $request)
    {
        $request->validate([
            'slot_number' => 'required|unique:parking_slots,slot_number',
            'location' => 'required',
            'type' => 'required',
            'hourly_rate' => 'required|numeric|min:0',
        ]);

        ParkingSlot::create([
            'slot_number' => $request->slot_number,
            'location' => $request->location,
            'type' => $request->type,
            'hourly_rate' => $request->hourly_rate,
            'status' => 'available',
        ]);

        return back()->with('success', 'Parking slot created successfully.');
    }

    public function destroySlot($id)
    {
        $slot = ParkingSlot::findOrFail($id);
        $slot->delete();
        return back()->with('success', 'Parking slot deleted successfully.');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot delete an administrator.');
        }
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }
}