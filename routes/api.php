<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TvLoginController;
use App\Http\Controllers\Api\TvTemplateController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/tv/login', [TvLoginController::class, 'login']);
Route::post('/tv/generate-pair-code', [\App\Http\Controllers\Api\TvPairController::class, 'generatePairCode']);
Route::post('/tv/pair-status', [\App\Http\Controllers\Api\TvPairController::class, 'checkStatus']);

Route::middleware('tv_token')->group(function () {
    Route::get('/tv/template/check-version', [TvTemplateController::class, 'checkVersion']);
    Route::get('/tv/flights', [\App\Http\Controllers\Api\TvFlightController::class, 'getFlights']);
    Route::post('/tv/flights/refresh', [\App\Http\Controllers\Api\TvFlightController::class, 'refreshFlights']);
});
