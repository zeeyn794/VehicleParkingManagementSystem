<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParkingSlot;
use App\Models\ParkingLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class DashboardController extends Controller
{

    public function index()
    {
        $totalSlots = ParkingSlot::count();
        $occupied = ParkingSlot::whereHas('parkingLogs', function($q) {
            $q->where('exit_time', '>', now());
        })->count();
        
        $available = $totalSlots - $occupied;
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $totalEarnings = ParkingLog::whereNotNull('total_fee')
            ->where('total_fee', '>', 0)
            ->where('exit_time', '<=', now())
            ->sum('total_fee');

        return view('admin.dashboard', compact('totalSlots', 'occupied', 'available', 'totalUsers', 'totalEarnings'));
    }

    public function slots(Request $request)
    {
        $query = ParkingSlot::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('slot_number', 'like', "%$search%")
                  ->orWhere('location', 'like', "%$search%")
                  ->orWhere('type', 'like', "%$search%");
        }

        $slots = $query->get()->map(function($slot) {
            $isOccupied = $slot->parkingLogs()
                ->where('exit_time', '>', now())
                ->exists();
            
            $slot->display_status = $isOccupied ? 'occupied' : 'available';
            return $slot;
        });
        
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
                    $sq->where('license_plate', 'like', "%$search%")
                      ->orWhere('type', 'like', "%$search%");
                });
            });
        }

        $logs = $query->latest()->paginate(10)->withQueryString();
        return view('admin.logs', compact('logs'));
    }

    public function exportLogs(Request $request)
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
                    $sq->where('license_plate', 'like', "%$search%")
                      ->orWhere('type', 'like', "%$search%");
                });
            });
        }

        $logs = $query->latest()->get();
        $filename = "parking_logs_" . date('Y-m-d_H-i-s') . ".csv";
        
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
            
            fputcsv($file, ['User', 'Slot', 'Vehicle', 'Type', 'Entry Time', 'Exit Time', 'Total Fee']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->user->name ?? 'Unknown User',
                    $log->parkingSlot->slot_number ?? 'N/A',
                    $log->vehicle->license_plate ?? 'N/A',
                    ucfirst($log->vehicle->type ?? 'Car'),
                    $log->entry_time ? $log->entry_time->format('Y-m-d H:i:s') : '-',
                    $log->exit_time ? $log->exit_time->format('Y-m-d H:i:s') : '-',
                    number_format($log->total_fee, 2)
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function earnings(Request $request)
    {
        $baseQuery = fn() => ParkingLog::whereNotNull('total_fee')
            ->where('total_fee', '>', 0)
            ->where('exit_time', '<=', now());

        $todayEarnings = $baseQuery()->whereDate('exit_time', \Carbon\Carbon::today())->sum('total_fee');
        $monthEarnings = $baseQuery()->whereMonth('exit_time', \Carbon\Carbon::now()->month)
                                    ->whereYear('exit_time', \Carbon\Carbon::now()->year)
                                    ->sum('total_fee');
        $totalEarnings = $baseQuery()->sum('total_fee');

        $query = ParkingLog::with(['user', 'parkingSlot', 'vehicle.user'])
            ->whereNotNull('total_fee')
            ->where('total_fee', '>', 0)
            ->where('exit_time', '<=', now());

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($sq) use ($search) {
                    $sq->where('name', 'like', "%$search%");
                })->orWhereHas('vehicle.user', function($sq) use ($search) {
                    $sq->where('name', 'like', "%$search%");
                })->orWhereHas('parkingSlot', function($sq) use ($search) {
                    $sq->where('slot_number', 'like', "%$search%");
                })->orWhereHas('vehicle', function($sq) use ($search) {
                    $sq->where('license_plate', 'like', "%$search%")
                      ->orWhere('type', 'like', "%$search%");
                });
            });
        }

        $transactions = $query->latest()->paginate(15)->withQueryString();
        
        return view('admin.earnings', compact('todayEarnings', 'monthEarnings', 'totalEarnings', 'transactions'));
    }

    public function exportEarnings(Request $request)
    {
        $query = ParkingLog::with(['user', 'parkingSlot', 'vehicle.user'])
            ->whereNotNull('total_fee')
            ->where('total_fee', '>', 0)
            ->where('exit_time', '<=', now());

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($sq) use ($search) {
                    $sq->where('name', 'like', "%$search%");
                })->orWhereHas('vehicle.user', function($sq) use ($search) {
                    $sq->where('name', 'like', "%$search%");
                })->orWhereHas('parkingSlot', function($sq) use ($search) {
                    $sq->where('slot_number', 'like', "%$search%");
                })->orWhereHas('vehicle', function($sq) use ($search) {
                    $sq->where('license_plate', 'like', "%$search%")
                      ->orWhere('type', 'like', "%$search%");
                });
            });
        }

        $transactions = $query->latest()->get();
        $filename = "earnings_report_" . date('Y-m-d_H-i-s') . ".csv";
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($transactions) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['Transaction ID', 'Date', 'User', 'Slot', 'Vehicle', 'Type', 'Amount Collected']);

            foreach ($transactions as $t) {
                fputcsv($file, [
                    'TRX-' . str_pad($t->id, 5, '0', STR_PAD_LEFT),
                    $t->created_at->format('Y-m-d H:i:s'),
                    $t->user->name ?? $t->vehicle->user->name ?? 'Unknown User',
                    $t->parkingSlot->slot_number ?? 'N/A',
                    $t->license_plate ?? $t->vehicle->license_plate ?? 'N/A',
                    ucfirst($t->vehicle->type ?? 'Car'),
                    number_format($t->total_fee, 2)
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function notifications()
    {
        $notifications = $this->getLatestNotifications(50);
        return view('admin.notifications', compact('notifications'));
    }

    private function getLatestNotifications($limit = 5)
    {
        $notifs = collect();

        $users = User::where('role', '!=', 'admin')->latest()->limit($limit)->get();
        foreach ($users as $user) {
            $notifs->push([
                'type' => 'user',
                'title' => 'New User Registered',
                'message' => "{$user->name} has joined the system.",
                'icon' => 'fas fa-user-plus',
                'bg' => 'rgba(34, 211, 238, 0.1)',
                'color' => 'var(--secondary-color)',
                'time' => $user->created_at,
            ]);
        }

        $logs = ParkingLog::with(['user', 'parkingSlot', 'vehicle'])->latest()->limit($limit)->get();
        foreach ($logs as $log) {
            $notifs->push([
                'type' => 'parking',
                'title' => 'Parking Activity',
                'message' => ($log->exit_time && $log->exit_time <= now()) 
                    ? "{$log->vehicle->license_plate} has exited Slot {$log->parkingSlot->slot_number}."
                    : "{$log->vehicle->license_plate} has parked in Slot {$log->parkingSlot->slot_number}.",
                'icon' => 'fas fa-parking',
                'bg' => 'rgba(245, 48, 3, 0.1)',
                'color' => 'var(--primary-color)',
                'time' => $log->created_at,
            ]);
        }

        return $notifs->sortByDesc('time')->take($limit);
    }

    public function users(Request $request)
    {
        $query = User::where('role', '!=', 'admin');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $users = $query->get();
        return view('admin.users', compact('users'));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('admin.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'current_password' => ['nullable', 'current_password'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function storeSlot(Request $request)
    {
        $request->validate([
            'slot_number' => 'required|unique:parking_slots,slot_number',
            'location' => 'required',
            'type' => 'required',
        ]);

        ParkingSlot::create([
            'slot_number' => $request->slot_number,
            'location' => $request->location,
            'type' => $request->type,
            'hourly_rate' => $request->hourly_rate ?? 0,
            'status' => 'available',
        ]);

        return back()->with('success', 'Parking slot created successfully.');
    }

    public function updateSlot(Request $request, $id)
    {
        $slot = ParkingSlot::findOrFail($id);

        $request->validate([
            'slot_number' => 'required|unique:parking_slots,slot_number,' . $slot->id,
            'location' => 'required',
            'type' => 'required',
            'status' => 'required|in:available,occupied,maintenance',
        ]);

        $slot->update([
            'slot_number' => $request->slot_number,
            'location' => $request->location,
            'type' => $request->type,
            'hourly_rate' => $request->hourly_rate ?? $slot->hourly_rate,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Parking slot updated successfully.');
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