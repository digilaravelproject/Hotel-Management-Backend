<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\LoginController as SuperLoginController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperDashboardController;
use App\Http\Controllers\SuperAdmin\HotelAdminController as SuperHotelController;
use App\Http\Controllers\SuperAdmin\PlanController as SuperPlanController;
use App\Http\Controllers\SuperAdmin\AmenityController as SuperAmenityController;
use App\Http\Controllers\SuperAdmin\DeviceController as SuperDeviceController;
use App\Http\Controllers\SuperAdmin\TemplateController as SuperTemplateController;

use App\Http\Controllers\SuperAdmin\FirebaseSettingsController as SuperFirebaseSettingsController;

/*
|--------------------------------------------------------------------------
| Super Admin Routes
|--------------------------------------------------------------------------
*/

Route::get('/super-admin/login', [SuperLoginController::class, 'showLoginForm'])->name('super-admin.login');
Route::post('/super-admin/login', [SuperLoginController::class, 'login']);
Route::post('/super-admin/logout', [SuperLoginController::class, 'logout'])->name('super-admin.logout');

// 2FA Auth Routes
Route::get('/2fa/verify', [\App\Http\Controllers\TwoFactorController::class, 'showVerifyForm'])->name('2fa.verify');
Route::post('/2fa/verify', [\App\Http\Controllers\TwoFactorController::class, 'verify']);

// Super Admin Panel (Protected by custom super_admin middleware)
Route::middleware(['super_admin', '2fa'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/dashboard', [SuperDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [SuperDashboardController::class, 'profileForm'])->name('profile');
    Route::post('/profile', [SuperDashboardController::class, 'updateProfile']);

    // 2FA Management Endpoints
    Route::post('/2fa/generate', [\App\Http\Controllers\TwoFactorController::class, 'generate'])->name('2fa.generate');
    Route::post('/2fa/enable', [\App\Http\Controllers\TwoFactorController::class, 'enable'])->name('2fa.enable');
    Route::post('/2fa/disable', [\App\Http\Controllers\TwoFactorController::class, 'disable'])->name('2fa.disable');

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

    // Manage OTTs / Applications Master Catalog
    Route::get('ott-master/{id}/toggle-status', [\App\Http\Controllers\SuperAdmin\OttMasterController::class, 'toggleStatus'])->name('ott-master.toggle-status');
    Route::resource('ott-master', \App\Http\Controllers\SuperAdmin\OttMasterController::class)->except(['create', 'edit', 'show'])->names('ott-master');

    // TV Templates Management
    Route::get('templates', [SuperTemplateController::class, 'index'])->name('templates.index');
    Route::post('templates', [SuperTemplateController::class, 'store'])->name('templates.store');
    Route::post('templates/{id}/toggle-active', [SuperTemplateController::class, 'toggleActive'])->name('templates.toggle-active');

    // Firebase FCM Real-Time Engine Management
    Route::get('firebase-settings', [SuperFirebaseSettingsController::class, 'index'])->name('firebase-settings.index');
    Route::post('firebase-settings', [SuperFirebaseSettingsController::class, 'update'])->name('firebase-settings.update');
    Route::post('firebase-settings/test-push', [SuperFirebaseSettingsController::class, 'testPush'])->name('firebase-settings.test-push');
    Route::post('firebase-settings/test-firestore', [SuperFirebaseSettingsController::class, 'testFirestore'])->name('firebase-settings.test-firestore');
});
