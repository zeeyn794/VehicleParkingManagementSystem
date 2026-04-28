<?php

use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ModernDashboardController;
use App\Http\Controllers\ProfileController;
use App\Models\ParkingLog;
use App\Models\ParkingSlot;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [ModernDashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [ModernDashboardController::class, 'index'])->name('dashboard');
    Route::post('/book', [ModernDashboardController::class, 'bookParking'])->name('book');
    Route::post('/extend', [ModernDashboardController::class, 'extendParking'])->name('extend');
    Route::post('/end', [ModernDashboardController::class, 'endParking'])->name('end');
    Route::get('/slots', [ModernDashboardController::class, 'getParkingSlots'])->name('slots');
    Route::get('/history', [ModernDashboardController::class, 'getParkingHistory'])->name('history');
    Route::post('/vehicles', [ModernDashboardController::class, 'addVehicle'])->name('vehicles.add');
    Route::post('/payments', [ModernDashboardController::class, 'addPaymentMethod'])->name('payments.add');
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

