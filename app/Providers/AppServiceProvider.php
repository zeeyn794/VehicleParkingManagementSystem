<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Models\ParkingLog;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * 
     */
    public function register(): void
    {
        //
    }

    /**
     */
    public function boot(): void
    {
        View::composer('layouts.modern-admin', function ($view) {
            $notifs = collect();

            $users = User::where('role', '!=', 'admin')->latest()->limit(5)->get();
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

            $logs = ParkingLog::with(['user', 'parkingSlot', 'vehicle'])->latest()->limit(5)->get();
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

            $recentNotifications = $notifs->sortByDesc('time')->take(5);
            $view->with('recentNotifications', $recentNotifications);
        });
    }
}
