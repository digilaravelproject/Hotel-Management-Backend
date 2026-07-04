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

Route::middleware('tv_token')->group(function () {
    Route::get('/tv/template/check-version', [TvTemplateController::class, 'checkVersion']);
});
