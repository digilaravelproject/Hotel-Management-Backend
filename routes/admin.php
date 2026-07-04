<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\LoginController as SuperLoginController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperDashboardController;
use App\Http\Controllers\SuperAdmin\HotelAdminController as SuperHotelController;
use App\Http\Controllers\SuperAdmin\PlanController as SuperPlanController;
use App\Http\Controllers\SuperAdmin\AmenityController as SuperAmenityController;
use App\Http\Controllers\SuperAdmin\DeviceController as SuperDeviceController;

/*
|--------------------------------------------------------------------------
| Super Admin Routes
|--------------------------------------------------------------------------
*/

// Super Admin Auth routes
Route::get('/super-admin/login', [SuperLoginController::class, 'showLoginForm'])->name('super-admin.login');
Route::post('/super-admin/login', [SuperLoginController::class, 'login']);
Route::post('/super-admin/logout', [SuperLoginController::class, 'logout'])->name('super-admin.logout');

// Super Admin Panel (Protected by custom super_admin middleware)
Route::middleware(['super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/dashboard', [SuperDashboardController::class, 'index'])->name('dashboard');
    Route::post('/profile/update', [SuperDashboardController::class, 'updateProfile'])->name('profile.update');

    // Hotels CRUD and Status Toggles
    Route::get('/hotels/{id}/toggle-status', [SuperHotelController::class, 'toggleStatus'])->name('hotels.toggle-status');
    Route::post('/hotels/{id}/toggle-approval', [SuperHotelController::class, 'toggleApproval'])->name('hotels.toggle-approval');
    Route::resource('hotels', SuperHotelController::class);

    // Amenities management for Super Admin
    Route::get('/hotels/{hotel_id}/amenities', [SuperAmenityController::class, 'index'])->name('hotels.amenities');
    Route::get('/amenities/{id}/toggle-status', [SuperAmenityController::class, 'toggleStatus'])->name('amenities.toggle-status');
    Route::put('/amenities/{id}', [SuperAmenityController::class, 'update'])->name('amenities.update');
    Route::delete('/amenities/{id}', [SuperAmenityController::class, 'destroy'])->name('amenities.destroy');

    // Plans CRUD and Status Toggles
    Route::get('/plans/{id}/toggle-status', [SuperPlanController::class, 'toggleStatus'])->name('plans.toggle-status');
    Route::resource('plans', SuperPlanController::class)->except(['show']);

    // Connected Devices
    Route::resource('devices', SuperDeviceController::class)->only(['index', 'destroy']);
});
