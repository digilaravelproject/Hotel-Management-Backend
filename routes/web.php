<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\HotelAdmin\RegistrationController;
use App\Http\Controllers\HotelAdmin\LoginController as HotelLoginController;
use App\Http\Controllers\HotelAdmin\DashboardController as HotelDashboardController;
use App\Http\Controllers\SuperAdmin\LoginController as SuperLoginController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperDashboardController;
use App\Http\Controllers\SuperAdmin\HotelAdminController as SuperHotelController;
use App\Http\Controllers\SuperAdmin\PlanController as SuperPlanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public landing and registration routes
Route::get('/', [LandingPageController::class, 'index'])->name('landing');
Route::post('/register/suggest-plan', [LandingPageController::class, 'suggestPlan'])->name('register.suggest-plan');
Route::post('/register/create-order', [RegistrationController::class, 'createOrder'])->name('register.create-order');
Route::post('/register/complete', [RegistrationController::class, 'completeRegistration'])->name('register.complete');
Route::get('/register/success', [RegistrationController::class, 'showSuccess'])->name('register.success');

// Hotel Admin Auth routes
Route::get('/hotel/login', [HotelLoginController::class, 'showLoginForm'])->name('hotel.login');
Route::post('/hotel/login', [HotelLoginController::class, 'login']);
Route::post('/hotel/logout', [HotelLoginController::class, 'logout'])->name('hotel.logout');

// Hotel Admin Dashboard (Protected by custom hotel_admin middleware)
Route::middleware(['hotel_admin'])->group(function () {
    Route::get('/hotel/dashboard', [HotelDashboardController::class, 'index'])->name('hotel.dashboard');
    Route::post('/hotel/subscribe', [HotelDashboardController::class, 'subscribe'])->name('hotel.subscribe');
});

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

    // Plans CRUD and Status Toggles
    Route::get('/plans/{id}/toggle-status', [SuperPlanController::class, 'toggleStatus'])->name('plans.toggle-status');
    Route::resource('plans', SuperPlanController::class)->except(['show']);
});
