<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TvLoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/tv/login', [TvLoginController::class, 'login']);
