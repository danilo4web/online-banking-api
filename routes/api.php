<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BitcoinController;
use App\Http\Controllers\API\ExtractController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AccountDepositController;
use App\Http\Controllers\API\AccountBalanceController;
use App\Http\Controllers\API\AccountCreateController;
use App\Http\Controllers\API\BitcoinQuoteController;
use App\Http\Controllers\API\BitcoinPurchaseController;
use App\Http\Controllers\API\BitcoinSellController;
use App\Http\Controllers\API\BitcoinPositionController;
use App\Http\Controllers\API\BitcoinVolumeController;
use App\Http\Controllers\API\BitcoinHistoryController;

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
Route::post('account', [AccountCreateController::class, 'create']);
Route::post('login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:api')->group(function () {
    Route::post('account/deposit', [AccountDepositController::class, 'deposit']);
    Route::get('account/balance', [AccountBalanceController::class, 'balance']);
    Route::get('btc/price', [BitcoinQuoteController::class, 'getBitcoinQuote']);
    Route::post('btc/purchase', [BitcoinPurchaseController::class, 'purchase']);
    Route::post('btc/sell', [BitcoinSellController::class, 'sell']);
    Route::get('btc', [BitcoinPositionController::class, 'position']);
    Route::get('extract', [ExtractController::class, 'index']);
    Route::get('volume', [BitcoinVolumeController::class, 'getBitcoinVolume']);
    Route::get('history', [BitcoinHistoryController::class, 'getHistoricalValues']);
});
