<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParkingSlot;
use App\Models\ParkingLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{

    public function index()
    {
        $totalSlots = ParkingSlot::count();
        $occupied = ParkingSlot::where('status', 'occupied')->count();
        $available = $totalSlots - $occupied;
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $totalEarnings = ParkingLog::whereNotNull('total_fee')
            ->where('total_fee', '>', 0)
            ->where('exit_time', '<=', now())
            ->sum('total_fee');

        return view('admin.dashboard', compact('totalSlots', 'occupied', 'available', 'totalUsers', 'totalEarnings'));
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
                    $sq->where('license_plate', 'like', "%$search%");
                });
            });
        }

        $transactions = $query->latest()->paginate(15)->withQueryString();
        
        return view('admin.earnings', compact('todayEarnings', 'monthEarnings', 'totalEarnings', 'transactions'));
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

    public function notifications()
    {
        $notifications = collect();

        $notifications->push([
            'type' => 'system',
            'title' => 'System Ready',
            'message' => 'Admin dashboard is fully operational and monitoring all slots.',
            'time' => now()->subHours(5),
            'icon' => 'fas fa-shield-alt',
            'bg' => 'rgba(245, 48, 3, 0.08)',
            'color' => 'var(--primary-color)',
        ]);

        $users = User::where('role', '!=', 'admin')->latest()->limit(20)->get();
        foreach ($users as $user) {
            $notifications->push([
                'type' => 'user',
                'title' => 'New User Registered',
                'message' => "{$user->name} has joined the system.",
                'time' => $user->created_at,
                'icon' => 'fas fa-user-plus',
                'bg' => 'rgba(34, 211, 238, 0.1)',
                'color' => 'var(--secondary-color)',
            ]);
        }

        $logs = ParkingLog::with(['user', 'parkingSlot', 'vehicle'])->latest()->limit(20)->get();
        foreach ($logs as $log) {
            $notifications->push([
                'type' => 'parking',
                'title' => 'Parking Activity',
                'message' => ($log->exit_time && $log->exit_time <= now())
                    ? ($log->vehicle->license_plate ?? 'A vehicle') . " has exited Slot " . ($log->parkingSlot->slot_number ?? 'N/A') . "."
                    : ($log->vehicle->license_plate ?? 'A vehicle') . " has parked in Slot " . ($log->parkingSlot->slot_number ?? 'N/A') . ".",
                'time' => $log->created_at,
                'icon' => 'fas fa-parking',
                'bg' => 'rgba(245, 48, 3, 0.1)',
                'color' => 'var(--primary-color)',
            ]);
        }

        $notifications = $notifications->sortByDesc('time')->values();

        return view('admin.notifications', compact('notifications'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function exportLogs()
    {
        $logs = ParkingLog::with(['user', 'parkingSlot', 'vehicle'])->latest()->get();
        $filename = "parking_logs_" . date('Y-m-d') . ".csv";
        
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
            
            fputcsv($file, ['ID', 'User', 'Vehicle', 'Slot', 'Entry Time', 'Exit Time', 'Fee', 'Status']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user->name ?? 'N/A',
                    $log->vehicle->license_plate ?? 'N/A',
                    $log->parkingSlot->slot_number ?? 'N/A',
                    $log->entry_time,
                    $log->exit_time,
                    $log->total_fee,
                    $log->exit_time > now() ? 'Active' : 'Completed'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportEarnings()
    {
        $transactions = ParkingLog::whereNotNull('total_fee')
            ->where('total_fee', '>', 0)
            ->where('exit_time', '<=', now())
            ->with(['user', 'vehicle', 'parkingSlot'])
            ->latest()
            ->get();
            
        $filename = "earnings_report_" . date('Y-m-d') . ".csv";
        
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
            
            fputcsv($file, ['Date', 'Transaction ID', 'User', 'Vehicle', 'Slot', 'Amount', 'Payment Method']);

            foreach ($transactions as $txn) {
                fputcsv($file, [
                    $txn->exit_time->format('Y-m-d H:i'),
                    'TXN-' . str_pad($txn->id, 6, '0', STR_PAD_LEFT),
                    $txn->user->name ?? 'N/A',
                    $txn->vehicle->license_plate ?? 'N/A',
                    $txn->parkingSlot->slot_number ?? 'N/A',
                    $txn->total_fee,
                    ucfirst($txn->payment_method ?? 'Cash')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function liveStats()
    {
        $totalSlots = ParkingSlot::count();

        $occupied = ParkingLog::where('exit_time', '>', now())
            ->distinct('parking_slot_id')
            ->count('parking_slot_id');

        $available = $totalSlots - $occupied;

        $totalUsers = User::where('role', '!=', 'admin')->count();

        $totalEarnings = ParkingLog::whereNotNull('total_fee')
            ->where('total_fee', '>', 0)
            ->where('exit_time', '<=', now())
            ->sum('total_fee');

        return response()->json([
            'totalSlots'    => $totalSlots,
            'occupied'      => $occupied,
            'available'     => $available,
            'totalUsers'    => $totalUsers,
            'totalEarnings' => '₱' . number_format($totalEarnings, 2),
        ]);
    }

    public function liveSlots()
    {
        $slots = ParkingSlot::all()->map(function ($slot) {
            $isActive = ParkingLog::where('parking_slot_id', $slot->id)
                ->where('exit_time', '>', now())
                ->exists();

            $status = $isActive ? 'occupied' : ($slot->status === 'maintenance' ? 'maintenance' : 'available');

            return [
                'id'     => $slot->id,
                'status' => $status,
            ];
        });

        return response()->json(['slots' => $slots]);
    }

    public function liveStream(Request $request): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ];

        return response()->stream(function () {
            $lastPayloadHash = null;
            $lastPingAt = microtime(true);

            while (!connection_aborted()) {
                $now = now();

                $totalSlots = ParkingSlot::count();

                $activeSlotIds = ParkingLog::where('exit_time', '>', $now)
                    ->pluck('parking_slot_id')
                    ->unique()
                    ->values();

                $occupied = $activeSlotIds->count();
                $available = max(0, $totalSlots - $occupied);

                $totalUsers = User::where('role', '!=', 'admin')->count();

                $totalEarnings = ParkingLog::whereNotNull('total_fee')
                    ->where('total_fee', '>', 0)
                    ->where('exit_time', '<=', $now)
                    ->sum('total_fee');

                $todayEarnings = ParkingLog::whereNotNull('total_fee')
                    ->where('total_fee', '>', 0)
                    ->where('exit_time', '<=', $now)
                    ->whereDate('exit_time', \Carbon\Carbon::today())
                    ->sum('total_fee');

                $monthEarnings = ParkingLog::whereNotNull('total_fee')
                    ->where('total_fee', '>', 0)
                    ->where('exit_time', '<=', $now)
                    ->whereMonth('exit_time', \Carbon\Carbon::now()->month)
                    ->whereYear('exit_time', \Carbon\Carbon::now()->year)
                    ->sum('total_fee');

                $slots = ParkingSlot::query()
                    ->select(['id', 'status'])
                    ->get()
                    ->map(function ($slot) use ($activeSlotIds) {
                        $isActive = $activeSlotIds->contains($slot->id);
                        $status = $isActive ? 'occupied' : ($slot->status === 'maintenance' ? 'maintenance' : 'available');

                        return [
                            'id' => $slot->id,
                            'status' => $status,
                        ];
                    })
                    ->values();

                $payload = [
                    'stats' => [
                        'totalSlots' => $totalSlots,
                        'occupied' => $occupied,
                        'available' => $available,
                        'totalUsers' => $totalUsers,
                        'totalEarnings' => '₱' . number_format($totalEarnings, 2),
                        'todayEarnings' => '₱' . number_format($todayEarnings, 2),
                        'monthEarnings' => '₱' . number_format($monthEarnings, 2),
                    ],
                    'slots' => $slots,
                    'serverTime' => $now->toIso8601String(),
                ];

                $payloadJson = json_encode($payload);
                $payloadHash = md5($payloadJson ?: '');

                if ($payloadJson && $payloadHash !== $lastPayloadHash) {
                    echo "event: live\n";
                    echo "data: {$payloadJson}\n\n";
                    @ob_flush();
                    @flush();
                    $lastPayloadHash = $payloadHash;
                }

                if (microtime(true) - $lastPingAt >= 15) {
                    echo "event: ping\n";
                    echo "data: {}\n\n";
                    @ob_flush();
                    @flush();
                    $lastPingAt = microtime(true);
                }

                usleep(1_000_000); 
            }
        }, 200, $headers);
    }
}
