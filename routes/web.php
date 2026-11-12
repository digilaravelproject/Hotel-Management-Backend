<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Auth\CommonLoginController;
use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\ApkDownloadController;

$mainDomain = env('MAIN_DOMAIN', 'paxtvlauncher.com');
$appDomain  = env('APP_DOMAIN', 'app.paxtvlauncher.com');

/*
|--------------------------------------------------------------------------
| 1. Main Landing Domain (https://paxtvlauncher.com)
|--------------------------------------------------------------------------
| Serves the official Pax TV Launcher marketing website, lead generation form,
| thank-you page, and public informational links.
*/
Route::domain($mainDomain)->group(function () {

    // Root URL serves the official landing page (previously served at /web)
    Route::get('/', [LandingPageController::class, 'web'])->name('web.home');

    // 301 Permanent Redirect for any legacy /web visits to the new root /
    Route::get('/web', function () {
        return redirect()->route('web.home', [], 301);
    });

    // Dedicated Thank-You Page after demo request submission
    Route::get('/thank-you', [LandingPageController::class, 'thankYou'])->name('thank-you');

    // Contact & Lead Generation Form Submission (SEC-08: Rate limited)
    Route::post('/contact-us', [LandingPageController::class, 'submitContact'])->middleware('throttle:10,1')->name('contact.submit');
    Route::get('/contact-us', [LandingPageController::class, 'contact'])->name('contact-us');

    // Secure 7-Day Signed APK Package Download
    Route::get('/download/app', [ApkDownloadController::class, 'download'])->name('app.apk.download');

    // Information & Support Pages
    Route::get('/privacy-policy', [LandingPageController::class, 'privacy'])->name('privacy-policy');
    Route::post('/register/suggest-plan', [LandingPageController::class, 'suggestPlan'])->middleware('throttle:30,1')->name('register.suggest-plan');
});

// Canonical 301 SEO Redirect: www.paxtvlauncher.com -> paxtvlauncher.com
if (!empty($mainDomain) && !str_starts_with($mainDomain, 'www.')) {
    Route::domain('www.' . $mainDomain)->group(function () use ($mainDomain) {
        Route::any('{any}', function ($any = '') use ($mainDomain) {
            return redirect()->to('https://' . $mainDomain . '/' . ltrim($any, '/'), 301);
        })->where('any', '.*');
    });
}

/*
|--------------------------------------------------------------------------
| 2. Application Subdomain (https://app.paxtvlauncher.com)
|--------------------------------------------------------------------------
| Serves authentication, login redirects, admin portals, and application logic.
*/
Route::domain($appDomain)->group(function () {

    // Root URL on app subdomain redirects straight to login
    Route::get('/', function () {
        return redirect()->route('login');
    })->name('landing');

    // Common Authentication Routes
    Route::get('/login', [CommonLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [CommonLoginController::class, 'login'])->middleware('throttle:15,1');
    Route::post('/logout', [CommonLoginController::class, 'logout'])->name('logout');

    // Payment Gateway Callbacks (PayU, etc.)
    Route::match(['get', 'post'], '/payment/success', [PaymentCallbackController::class, 'success'])->name('payment.success');
    Route::match(['get', 'post'], '/payment/failure', [PaymentCallbackController::class, 'failure'])->name('payment.failure');
});

/*
|--------------------------------------------------------------------------
| 3. Local Development Fallback
|--------------------------------------------------------------------------
| Allows local testing (e.g. 127.0.0.1, localhost) without custom domain configs.
*/
if (app()->environment('local')) {
    Route::get('/', [LandingPageController::class, 'web']);
    Route::get('/web', [LandingPageController::class, 'web']);
    Route::get('/thank-you', [LandingPageController::class, 'thankYou']);
    Route::get('/download/app', [ApkDownloadController::class, 'download']);
}
