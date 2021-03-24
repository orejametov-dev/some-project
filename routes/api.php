<?php

use App\Http\Controllers\Api\Conditions\ConditionsController;
use App\Http\Controllers\Api\Merchants\MerchantsController;
use App\Http\Controllers\Api\Merchants\MerchantUsersController;
use App\Http\Controllers\Api\Stores\StoresController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/conditions', [ConditionsController::class, 'index']);
Route::get('/conditions/{id}', [ConditionsController::class, 'show']);

Route::get('/merchants', [MerchantsController::class, 'index']);
Route::get('/merchants/{id}', [MerchantsController::class, 'show']);

Route::get('/merchant/users', [MerchantUsersController::class, 'index']);
Route::get('/merchant/users/{id}', [MerchantUsersController::class, 'show']);

Route::get('/stores', [StoresController::class, 'index']);
Route::get('/stores/{id}', [StoresController::class, 'show']);


