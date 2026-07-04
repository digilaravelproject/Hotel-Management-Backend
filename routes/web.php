<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider or bootstrap/app.php
| within a group which contains the "web" middleware group. Now create something great!
|
*/

// Public landing and registration routes
Route::get('/', [LandingPageController::class, 'index'])->name('landing');
Route::post('/register/suggest-plan', [LandingPageController::class, 'suggestPlan'])->name('register.suggest-plan');
