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

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group([
    'prefix' => 'currency',
    'as' => 'currency.',
    'middleware' => ['auth:api']
], function () {
    Route::get('/list', [CurrencyController::class, 'getCurrencies'])->name('list');
    Route::get('/exchange-rate/{currency}', [CurrencyController::class, 'getExchangeRate'])->name('exchange-rate');
    Route::get('/convert', [CurrencyController::class, 'convertCurrency'])->name('convert');
});
