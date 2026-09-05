<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HotelAdmin\RegistrationController;
use App\Http\Controllers\HotelAdmin\LoginController as HotelLoginController;
use App\Http\Controllers\HotelAdmin\ForgotPasswordController;
use App\Http\Controllers\HotelAdmin\ProfileController;
use App\Http\Controllers\HotelAdmin\AmenityController;
use App\Http\Controllers\HotelAdmin\DashboardController as HotelDashboardController;
use App\Http\Controllers\HotelAdmin\DeviceController as HotelDeviceController;
use App\Http\Controllers\HotelAdmin\GuestController as HotelGuestController;

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
Route::middleware(['hotel_admin', '2fa'])->group(function () {
    Route::get('/hotel/dashboard', [HotelDashboardController::class, 'index'])->name('hotel.dashboard');
    Route::post('/hotel/subscribe', [HotelDashboardController::class, 'subscribe'])->name('hotel.subscribe');
    Route::get('/hotel/profile', [ProfileController::class, 'showProfileForm'])->name('hotel.profile');
    Route::post('/hotel/profile', [ProfileController::class, 'updateProfile']);

    // 2FA Management Endpoints
    Route::post('/hotel/2fa/generate', [\App\Http\Controllers\TwoFactorController::class, 'generate'])->name('hotel.2fa.generate');
    Route::post('/hotel/2fa/enable', [\App\Http\Controllers\TwoFactorController::class, 'enable'])->name('hotel.2fa.enable');
    Route::post('/hotel/2fa/disable', [\App\Http\Controllers\TwoFactorController::class, 'disable'])->name('hotel.2fa.disable');
    Route::get('/hotel/hotel-info', [ProfileController::class, 'showHotelInfoForm'])->name('hotel.hotel-info');
    Route::post('/hotel/hotel-info', [ProfileController::class, 'updateHotelInfo']);
    Route::post('/hotel/hotel-info/delete-slider', [ProfileController::class, 'deleteSliderImage'])->name('hotel.hotel-info.delete-slider');
    Route::post('/hotel/hotel-info/delete-gallery', [ProfileController::class, 'deleteGalleryImage'])->name('hotel.hotel-info.delete-gallery');
    Route::post('/hotel/hotel-info/gallery/store', [ProfileController::class, 'storeGalleryItem'])->name('hotel.hotel-info.gallery.store');
    Route::post('/hotel/hotel-info/gallery/{id}/update', [ProfileController::class, 'updateGalleryItem'])->name('hotel.hotel-info.gallery.update');
    Route::post('/hotel/hotel-info/gallery/{id}/delete', [ProfileController::class, 'deleteGalleryItem'])->name('hotel.hotel-info.gallery.delete');
    
    // Amenities CRUD
    Route::get('/hotel/amenities/{id}/toggle-status', [AmenityController::class, 'toggleStatus'])->name('hotel.amenities.toggle-status');
    Route::resource('/hotel/amenities', AmenityController::class)->names('hotel.amenities');

    // Room Info CRUD
    Route::get('/hotel/room-infos/{id}/toggle-status', [\App\Http\Controllers\HotelAdmin\RoomInfoController::class, 'toggleStatus'])->name('hotel.room-infos.toggle-status');
    Route::resource('/hotel/room-infos', \App\Http\Controllers\HotelAdmin\RoomInfoController::class)->names('hotel.room-infos');

    // Hotel Facilities / Information Media CRUD
    Route::resource('/hotel/facilities', \App\Http\Controllers\HotelAdmin\HotelFacilityController::class)->names('hotel.facilities');

    // Connected TVs & OTT / Menu Configurations
    Route::get('/hotel/devices/{id}/ott', [HotelDeviceController::class, 'showRoomOtt'])->name('hotel.devices.ott');
    Route::post('/hotel/devices/{id}/ott', [HotelDeviceController::class, 'updateRoomOtt']);
    Route::post('/hotel/devices/{id}/ott/reset', [HotelDeviceController::class, 'resetRoomOtt'])->name('hotel.devices.ott.reset');
    Route::get('/hotel/devices/{id}/menus', [HotelDeviceController::class, 'showRoomMenus'])->name('hotel.devices.menus');
    Route::post('/hotel/devices/{id}/menus', [HotelDeviceController::class, 'updateRoomMenus']);
    Route::post('/hotel/devices/{id}/menus/reset', [HotelDeviceController::class, 'resetRoomMenus'])->name('hotel.devices.menus.reset');
    Route::post('/hotel/devices/pair', [HotelDeviceController::class, 'pairDeviceByCode'])->name('hotel.devices.pair');
    Route::resource('/hotel/devices', HotelDeviceController::class)->only(['index', 'destroy'])->names('hotel.devices');

    // OTT Package & Global Settings
    Route::get('/hotel/package', [\App\Http\Controllers\HotelAdmin\OttController::class, 'myPackage'])->name('hotel.package');
    Route::get('/hotel/ott-settings', [\App\Http\Controllers\HotelAdmin\OttController::class, 'globalSettings'])->name('hotel.ott-settings');
    Route::post('/hotel/ott-settings', [\App\Http\Controllers\HotelAdmin\OttController::class, 'updateGlobalSettings']);

    // Global Manage Menus
    Route::get('/hotel/menus', [\App\Http\Controllers\HotelAdmin\MenuController::class, 'index'])->name('hotel.menus.index');
    Route::post('/hotel/menus', [\App\Http\Controllers\HotelAdmin\MenuController::class, 'update']);

    // Guest Management CRUD
    Route::post('/hotel/guests/{id}/checkout', [HotelGuestController::class, 'checkout'])->name('hotel.guests.checkout');
    Route::resource('/hotel/guests', HotelGuestController::class)->names('hotel.guests');

    // Airport & Flight Settings
    Route::get('/hotel/flights', [\App\Http\Controllers\HotelAdmin\FlightController::class, 'index'])->name('hotel.flights.index');
    Route::post('/hotel/flights', [\App\Http\Controllers\HotelAdmin\FlightController::class, 'update'])->name('hotel.flights.update');

    // TV Themes & Styling
    Route::get('/hotel/themes', [\App\Http\Controllers\HotelAdmin\TvThemeController::class, 'index'])->name('hotel.themes.index');
    Route::post('/hotel/themes/select', [\App\Http\Controllers\HotelAdmin\TvThemeController::class, 'select'])->name('hotel.themes.select');
});
