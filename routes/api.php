<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CurrencyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

//Route::middleware('auth:api')->group(function () {
    Route::get('/currencies', [CurrencyController::class, 'getCurrencies']);
    Route::get('/exchange-rate/{currency}', [CurrencyController::class, 'getExchangeRate']);
    Route::post('/convert', [CurrencyController::class, 'convertCurrency']);
//});
