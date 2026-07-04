<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HotelAdmin\RegistrationController;
use App\Http\Controllers\HotelAdmin\LoginController as HotelLoginController;
use App\Http\Controllers\HotelAdmin\ForgotPasswordController;
use App\Http\Controllers\HotelAdmin\ProfileController;
use App\Http\Controllers\HotelAdmin\AmenityController;
use App\Http\Controllers\HotelAdmin\DashboardController as HotelDashboardController;
use App\Http\Controllers\HotelAdmin\DeviceController as HotelDeviceController;

/*
|--------------------------------------------------------------------------
| Hotel Admin Routes
|--------------------------------------------------------------------------
*/

// Hotel Registration process routes
Route::post('/register/create-order', [RegistrationController::class, 'createOrder'])->name('register.create-order');
Route::post('/register/complete', [RegistrationController::class, 'completeRegistration'])->name('register.complete');
Route::get('/register/success', [RegistrationController::class, 'showSuccess'])->name('register.success');

// Hotel Admin Auth routes
Route::get('/hotel/login', [HotelLoginController::class, 'showLoginForm'])->name('hotel.login');
Route::post('/hotel/login', [HotelLoginController::class, 'login']);
Route::post('/hotel/logout', [HotelLoginController::class, 'logout'])->name('hotel.logout');
Route::get('/hotel/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('hotel.forgot-password');
Route::post('/hotel/forgot-password', [ForgotPasswordController::class, 'sendResetOtp']);
Route::get('/hotel/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('hotel.reset-password');
Route::post('/hotel/reset-password', [ForgotPasswordController::class, 'reset']);

// Hotel Admin Dashboard (Protected by custom hotel_admin middleware)
Route::middleware(['hotel_admin'])->group(function () {
    Route::get('/hotel/dashboard', [HotelDashboardController::class, 'index'])->name('hotel.dashboard');
    Route::post('/hotel/subscribe', [HotelDashboardController::class, 'subscribe'])->name('hotel.subscribe');
    Route::get('/hotel/profile', [ProfileController::class, 'showProfileForm'])->name('hotel.profile');
    Route::post('/hotel/profile', [ProfileController::class, 'updateProfile']);
    Route::get('/hotel/hotel-info', [ProfileController::class, 'showHotelInfoForm'])->name('hotel.hotel-info');
    Route::post('/hotel/hotel-info', [ProfileController::class, 'updateHotelInfo']);
    
    // Amenities CRUD
    Route::get('/hotel/amenities/{id}/toggle-status', [AmenityController::class, 'toggleStatus'])->name('hotel.amenities.toggle-status');
    Route::resource('/hotel/amenities', AmenityController::class)->names('hotel.amenities');

    // Connected TVs
    Route::resource('/hotel/devices', HotelDeviceController::class)->only(['index', 'destroy'])->names('hotel.devices');
});
