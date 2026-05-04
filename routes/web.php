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
    Route::get('/history', [ModernDashboardController::class, 'historyPage'])->name('history');
    Route::post('/vehicles', [ModernDashboardController::class, 'addVehicle'])->name('vehicles.add');
    Route::post('/payments', [ModernDashboardController::class, 'addPaymentMethod'])->name('payments.add');
    Route::view('/parking', 'user.parking')->name('parking');
    Route::view('/session', 'user.session')->name('session');
    Route::view('/vehicles', 'user.vehicles')->name('vehicles');
    Route::view('/payments', 'user.payments')->name('payments');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/occupancy', [\App\Http\Controllers\Admin\DashboardController::class, 'occupancy'])->name('occupancy');
    Route::get('/slots', [\App\Http\Controllers\Admin\DashboardController::class, 'slots'])->name('slots');
    Route::get('/logs', [\App\Http\Controllers\Admin\DashboardController::class, 'logs'])->name('logs');
    Route::get('/earnings', [\App\Http\Controllers\Admin\DashboardController::class, 'earnings'])->name('earnings');
    Route::get('/users', [\App\Http\Controllers\Admin\DashboardController::class, 'users'])->name('users');
    Route::post('/slots', [\App\Http\Controllers\Admin\DashboardController::class, 'storeSlot'])->name('slots.store');
    Route::patch('/slots/{id}', [\App\Http\Controllers\Admin\DashboardController::class, 'updateSlot'])->name('slots.update');
    Route::delete('/slots/{id}', [\App\Http\Controllers\Admin\DashboardController::class, 'destroySlot'])->name('slots.destroy');
    Route::delete('/users/{id}', [\App\Http\Controllers\Admin\DashboardController::class, 'destroyUser'])->name('users.destroy');
    
    Route::get('/rates', [\App\Http\Controllers\Admin\RateController::class, 'index'])->name('rates');
    Route::patch('/rates/{id}', [\App\Http\Controllers\Admin\RateController::class, 'update'])->name('rates.update');
});

require __DIR__.'/auth.php';

