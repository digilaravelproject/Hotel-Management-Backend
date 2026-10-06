<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Distributor\AuthController;
use App\Http\Controllers\Distributor\DistributorController;

/*
|--------------------------------------------------------------------------
| Distributor Portal Routes
|--------------------------------------------------------------------------
*/

// Guest authentication routes
Route::prefix('distributor')->name('distributor.')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

// Protected Distributor Routes (requires web auth and distributor/super_admin role)
Route::middleware(['auth:web', 'role:distributor|super_admin'])->prefix('distributor')->name('distributor.')->group(function () {
    Route::get('dashboard', [DistributorController::class, 'dashboard'])->name('dashboard');

    // Hotels onboarded by distributor
    Route::get('hotels', [DistributorController::class, 'hotels'])->name('hotels.index');
    Route::get('hotels/create', [DistributorController::class, 'createHotel'])->name('hotels.create');
    Route::post('hotels', [DistributorController::class, 'storeHotel'])->name('hotels.store');

    // Package sales
    Route::get('sales', [DistributorController::class, 'sales'])->name('sales.index');
    Route::get('sales/create', [DistributorController::class, 'createSale'])->name('sales.create');
    Route::post('sales', [DistributorController::class, 'storeSale'])->name('sales.store');
});
