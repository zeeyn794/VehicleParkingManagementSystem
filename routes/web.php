<?php

use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Models\ParkingLog;
use App\Models\ParkingSlot;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (Auth::user()->role === 'admin') {
        return view('admin.dashboard', [
            'totalSlots' => 50,
            'availableSlots' => 38,
            'occupancyRate' => 24,
            'slots' => [] 
        ]);
    }

    $user = Auth::user();
    $activeSessions = $user ? ParkingLog::whereIn('vehicle_id', $user->vehicles->pluck('id'))
        ->where('exit_time', '>', now())
        ->with('parkingSlot')
        ->get() : collect();
    $userVehicles = $user->vehicles()->latest()->get();
    $availableSlots = ParkingSlot::where('status', 'available')->get();

    return view('dashboard', compact('activeSessions', 'userVehicles', 'availableSlots'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/dashboard/park', function () {
    // TODO: handle parking logic
    return redirect()->route('dashboard')->with('success', 'Parking booked successfully!');
})->middleware(['auth', 'verified'])->name('dashboard.park');

Route::middleware(['auth', 'verified'])->prefix('user')->name('user.')->group(function () {
    Route::view('/dashboard', 'user.dashboard')->name('dashboard');
    Route::view('/parking', 'user.parking')->name('parking');
    Route::view('/session', 'user.session')->name('session');
    Route::view('/vehicles', 'user.vehicles')->name('vehicles');
    Route::view('/history', 'user.history')->name('history');
    Route::view('/payments', 'user.payments')->name('payments');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

