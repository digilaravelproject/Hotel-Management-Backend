<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TvLoginController;
use App\Http\Controllers\Api\TvTemplateController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('throttle:20,1')->group(function () {
    Route::post('/tv/login', [TvLoginController::class, 'login']);
    Route::post('/tv/demo-login', [TvLoginController::class, 'demoLogin']);
    Route::post('/tv/generate-pair-code', [\App\Http\Controllers\Api\TvPairController::class, 'generatePairCode']);
});
Route::post('/tv/pair-status', [\App\Http\Controllers\Api\TvPairController::class, 'checkStatus'])->middleware('throttle:300,1');

Route::middleware(['tv_token', 'throttle:120,1'])->group(function () {
    Route::get('/tv/template/check-version', [TvTemplateController::class, 'checkVersion']);
    Route::get('/tv/check-template-version', [TvTemplateController::class, 'checkVersion']);
    Route::match(['get', 'post'], '/tv/heartbeat', [TvTemplateController::class, 'heartbeat']);
    Route::match(['get', 'post'], '/tv/ping', [TvTemplateController::class, 'heartbeat']);
    Route::get('/tv/flights', [\App\Http\Controllers\Api\TvFlightController::class, 'getFlights']);
    Route::post('/tv/flights/refresh', [\App\Http\Controllers\Api\TvFlightController::class, 'refreshFlights']);
});
