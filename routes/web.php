<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

<<<<<<< HEAD
Route::get('/dashboard', function () {
    return redirect()->route('user.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
=======
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::post('/dashboard/park', [DashboardController::class, 'park'])->middleware(['auth', 'verified'])->name('dashboard.park');
>>>>>>> 9a6d934946a83daefc39d8387bab15770bd7d04a

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

