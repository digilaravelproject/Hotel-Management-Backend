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
    Route::get('login', [\App\Http\Controllers\Auth\CommonLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [\App\Http\Controllers\Auth\CommonLoginController::class, 'login']);
    Route::post('logout', [\App\Http\Controllers\Auth\CommonLoginController::class, 'logout'])->name('logout');
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
    Route::post('sales/order', [DistributorController::class, 'createSaleOrder'])->name('sales.order');

    // Connected devices
    Route::get('devices', [DistributorController::class, 'devices'])->name('devices.index');
    Route::delete('devices/{id}', [DistributorController::class, 'destroyDevice'])->name('devices.destroy');

    // Digital Wallet & Commissions
    Route::get('wallet', [\App\Http\Controllers\Distributor\WalletController::class, 'index'])->name('wallet.index');
    Route::get('wallet/transactions', [\App\Http\Controllers\Distributor\WalletController::class, 'transactions'])->name('wallet.transactions');
    Route::get('wallet/withdrawals', [\App\Http\Controllers\Distributor\WalletController::class, 'withdrawals'])->name('wallet.withdrawals');
    Route::post('wallet/withdraw', [\App\Http\Controllers\Distributor\WalletController::class, 'requestWithdrawal'])->name('wallet.withdraw');

    // Tax Invoices & Billing
    Route::get('invoices', [DistributorController::class, 'invoices'])->name('invoices.index');
    Route::get('invoices/{id}', [DistributorController::class, 'showInvoice'])->name('invoices.show');
});

