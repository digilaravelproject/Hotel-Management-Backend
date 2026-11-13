<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\LoginController as SuperLoginController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperDashboardController;
use App\Http\Controllers\SuperAdmin\HotelAdminController as SuperHotelController;
use App\Http\Controllers\SuperAdmin\PlanController as SuperPlanController;
use App\Http\Controllers\SuperAdmin\TaxController as SuperTaxController;
use App\Http\Controllers\SuperAdmin\InvoiceController as SuperInvoiceController;
use App\Http\Controllers\SuperAdmin\AmenityController as SuperAmenityController;
use App\Http\Controllers\SuperAdmin\DeviceController as SuperDeviceController;
use App\Http\Controllers\SuperAdmin\TemplateController as SuperTemplateController;
use App\Http\Controllers\SuperAdmin\UserController as SuperUserController;

use App\Http\Controllers\SuperAdmin\FirebaseSettingsController as SuperFirebaseSettingsController;

use App\Http\Controllers\SuperAdmin\PaymentSettingController;
use App\Http\Controllers\SuperAdmin\MailSettingController;

/*
|--------------------------------------------------------------------------
| Super Admin Routes
|--------------------------------------------------------------------------
*/

Route::get('/super-admin/login', [\App\Http\Controllers\Auth\CommonLoginController::class, 'showLoginForm'])->name('super-admin.login');
Route::post('/super-admin/login', [\App\Http\Controllers\Auth\CommonLoginController::class, 'login'])->middleware('throttle:15,1');
Route::post('/super-admin/logout', [\App\Http\Controllers\Auth\CommonLoginController::class, 'logout'])->name('super-admin.logout');

// 2FA Auth Routes
Route::get('/2fa/verify', [\App\Http\Controllers\TwoFactorController::class, 'showVerifyForm'])->name('2fa.verify');
Route::post('/2fa/verify', [\App\Http\Controllers\TwoFactorController::class, 'verify'])->middleware('throttle:10,1');

// Super Admin Panel (Protected by custom super_admin middleware)
Route::middleware(['super_admin', '2fa'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/dashboard', [SuperDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [SuperDashboardController::class, 'profileForm'])->name('profile');
    Route::post('/profile', [SuperDashboardController::class, 'updateProfile']);

    // 2FA Management Endpoints
    Route::post('/2fa/generate', [\App\Http\Controllers\TwoFactorController::class, 'generate'])->name('2fa.generate');
    Route::post('/2fa/enable', [\App\Http\Controllers\TwoFactorController::class, 'enable'])->name('2fa.enable');
    Route::post('/2fa/disable', [\App\Http\Controllers\TwoFactorController::class, 'disable'])->name('2fa.disable');

    // Hotels CRUD, Status Toggles, and Super Admin Checkout
    Route::post('/hotels/{id}/toggle-status', [SuperHotelController::class, 'toggleStatus'])->name('hotels.toggle-status');
    Route::post('/hotels/{id}/toggle-approval', [SuperHotelController::class, 'toggleApproval'])->name('hotels.toggle-approval');
    Route::get('/hotels/{id}/menus', [\App\Http\Controllers\SuperAdmin\MenuController::class, 'edit'])->name('hotels.menus');
    Route::post('/hotels/{id}/menus', [\App\Http\Controllers\SuperAdmin\MenuController::class, 'update']);
    Route::post('/hotels/{id}/menus/reset', [\App\Http\Controllers\SuperAdmin\MenuController::class, 'reset'])->name('hotels.menus.reset');
    Route::get('/hotels/{id}/theme-preview', [\App\Http\Controllers\SuperAdmin\MenuController::class, 'themePreview'])->name('hotels.theme-preview');
    Route::get('/hotels/{id}/tv-data', [\App\Http\Controllers\SuperAdmin\MenuController::class, 'tvData'])->name('hotels.tv-data');
    Route::get('/hotels/{id}/pay', [SuperHotelController::class, 'showPayment'])->name('hotels.pay');
    Route::post('/hotels/{id}/pay', [SuperHotelController::class, 'processPayment']);
    Route::post('/hotels/{id}/pay/order', [SuperHotelController::class, 'createPaymentOrder'])->name('hotels.pay.order');
    Route::get('/hotels/{id}/addon', [SuperHotelController::class, 'showAddon'])->name('hotels.addon');
    Route::post('/hotels/{id}/addon', [SuperHotelController::class, 'processAddon']);
    Route::resource('hotels', SuperHotelController::class);

    // Amenities management for Super Admin
    Route::get('/hotels/{hotel_id}/amenities', [SuperAmenityController::class, 'index'])->name('hotels.amenities');
    Route::post('/amenities/{id}/toggle-status', [SuperAmenityController::class, 'toggleStatus'])->name('amenities.toggle-status');
    Route::put('/amenities/{id}', [SuperAmenityController::class, 'update'])->name('amenities.update');
    Route::delete('/amenities/{id}', [SuperAmenityController::class, 'destroy'])->name('amenities.destroy');

    // Plans CRUD, Status Toggles, and Per-TV live preview
    Route::get('/plans/{id}/calculate-preview', [SuperPlanController::class, 'calculatePreview'])->name('plans.calculate-preview');
    Route::post('/plans/{id}/toggle-status', [SuperPlanController::class, 'toggleStatus'])->name('plans.toggle-status');
    Route::resource('plans', SuperPlanController::class)->except(['show']);

    // Tax Management System
    Route::post('taxes/{id}/toggle-status', [SuperTaxController::class, 'toggleStatus'])->name('taxes.toggle-status');
    Route::resource('taxes', SuperTaxController::class)->except(['create', 'edit', 'show']);

    // Invoices Management System
    Route::get('invoices', [SuperInvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/{id}', [SuperInvoiceController::class, 'show'])->name('invoices.show');
    Route::post('invoices/{id}/mark-paid', [SuperInvoiceController::class, 'markPaid'])->name('invoices.mark-paid');

    // User Management & Roles/Permissions
    Route::post('users/{id}/toggle-status', [SuperUserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::resource('users', SuperUserController::class);

    // Connected Devices & Demo TV Management
    Route::delete('devices/{id}/purge', [SuperDeviceController::class, 'purgeDemoDevice'])->name('devices.purge');
    Route::post('devices/{id}/extend-trial', [SuperDeviceController::class, 'extendDemoTrial'])->name('devices.extend-trial');
    Route::resource('devices', SuperDeviceController::class)->only(['index', 'destroy']);

    // Manage OTTs / Applications Master Catalog
    Route::post('ott-master/{id}/toggle-status', [\App\Http\Controllers\SuperAdmin\OttMasterController::class, 'toggleStatus'])->name('ott-master.toggle-status');
    Route::resource('ott-master', \App\Http\Controllers\SuperAdmin\OttMasterController::class)->except(['create', 'edit', 'show'])->names('ott-master');

    // TV Themes & Templates Management
    Route::get('templates', [SuperTemplateController::class, 'index'])->name('templates.index');
    Route::post('templates', [SuperTemplateController::class, 'store'])->name('templates.store');
    Route::delete('templates/{id}', [SuperTemplateController::class, 'destroy'])->name('templates.destroy');
    Route::delete('templates/theme/{theme_id}', [SuperTemplateController::class, 'destroyTheme'])->name('templates.destroy-theme');
    Route::post('templates/purge-inactive', [SuperTemplateController::class, 'purgeInactiveBuilds'])->name('templates.purge-inactive');
    Route::post('templates/{id}/toggle-active', [SuperTemplateController::class, 'toggleActive'])->name('templates.toggle-active');
    Route::post('templates/{theme_id}/set-default', [SuperTemplateController::class, 'setDefaultTheme'])->name('templates.set-default');
    Route::get('templates/{id}/preview', [SuperTemplateController::class, 'preview'])->name('templates.preview');

    // Flight & Airport Management
    Route::get('flights', [\App\Http\Controllers\SuperAdmin\FlightSettingController::class, 'index'])->name('flights.index');
    Route::post('flights/settings', [\App\Http\Controllers\SuperAdmin\FlightSettingController::class, 'updateSettings'])->name('flights.settings.update');
    Route::post('flights/airports', [\App\Http\Controllers\SuperAdmin\FlightSettingController::class, 'storeAirport'])->name('flights.airports.store');
    Route::post('flights/airports/{id}/toggle', [\App\Http\Controllers\SuperAdmin\FlightSettingController::class, 'toggleAirportStatus'])->name('flights.airports.toggle');
    Route::post('flights/refresh/{iata}', [\App\Http\Controllers\SuperAdmin\FlightSettingController::class, 'forceRefresh'])->name('flights.refresh');

    // Firebase FCM Real-Time Engine Management
    Route::get('firebase-settings', [SuperFirebaseSettingsController::class, 'index'])->name('firebase-settings.index');
    Route::post('firebase-settings', [SuperFirebaseSettingsController::class, 'update'])->name('firebase-settings.update');
    Route::post('firebase-settings/test-push', [SuperFirebaseSettingsController::class, 'testPush'])->name('firebase-settings.test-push');
    Route::post('firebase-settings/test-firestore', [SuperFirebaseSettingsController::class, 'testFirestore'])->name('firebase-settings.test-firestore');

    // Application & System Branding Settings
    Route::get('settings', [\App\Http\Controllers\SuperAdmin\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\SuperAdmin\SettingController::class, 'update'])->name('settings.update');

    // Payment Gateway Settings & Multi-Gateway Architecture
    Route::get('payment-settings', [PaymentSettingController::class, 'index'])->name('payment-settings.index');
    Route::post('payment-settings', [PaymentSettingController::class, 'update'])->name('payment-settings.update');

    // Email & SMTP Server Settings
    Route::get('mail-settings', [MailSettingController::class, 'index'])->name('mail-settings.index');
    Route::post('mail-settings', [MailSettingController::class, 'update'])->name('mail-settings.update');
    Route::post('mail-settings/test', [MailSettingController::class, 'sendTestMail'])->name('mail-settings.test');

    // Commission Settings (Global)
    Route::get('commissions/settings', [\App\Http\Controllers\SuperAdmin\CommissionController::class, 'settings'])->name('commissions.settings');
    Route::post('commissions/settings', [\App\Http\Controllers\SuperAdmin\CommissionController::class, 'updateSettings'])->name('commissions.settings.update');

    // Distributor Withdrawal Requests Management
    Route::get('withdrawals', [\App\Http\Controllers\SuperAdmin\WithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::post('withdrawals/{id}/approve', [\App\Http\Controllers\SuperAdmin\WithdrawalController::class, 'approve'])->name('withdrawals.approve');
    Route::post('withdrawals/{id}/reject', [\App\Http\Controllers\SuperAdmin\WithdrawalController::class, 'reject'])->name('withdrawals.reject');

    // Website Demo Requests & Inquiries
    Route::get('inquiries', [\App\Http\Controllers\SuperAdmin\InquiryController::class, 'index'])->name('inquiries.index');
    Route::post('inquiries/{id}/resend', [\App\Http\Controllers\SuperAdmin\InquiryController::class, 'resendEmail'])->name('inquiries.resend');
    Route::put('inquiries/{id}/status', [\App\Http\Controllers\SuperAdmin\InquiryController::class, 'updateStatus'])->name('inquiries.status');
    Route::delete('inquiries/{id}', [\App\Http\Controllers\SuperAdmin\InquiryController::class, 'destroy'])->name('inquiries.destroy');
});

