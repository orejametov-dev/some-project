<?php

use App\Http\Controllers\ApiGate\App\CountersController;
use App\Http\Controllers\ApiGate\Conditions\ConditionsController;
use App\Http\Controllers\ApiGate\Merchants\MerchantsController;
use App\Http\Controllers\ApiGate\Merchants\MerchantUsersController;
use App\Http\Controllers\ApiGate\Merchants\RequestsController;
use App\Http\Controllers\ApiGate\Stores\StoresController;
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

Route::get('/merchants/{merchant_id}/conditions/{condition_id}', [ConditionsController::class, 'getConditionByMerchantId']);
Route::get('/merchants/{merchant_id}/stores/{store_id}', [StoresController::class, 'getStoreByMerchantId']);


Route::get('/merchants/users', [MerchantUsersController::class, 'index']);
Route::get('/merchants/users/{id}', [MerchantUsersController::class, 'show']);
Route::get('/merchants/users/{user_id}/by-user', [MerchantUsersController::class, 'getByUserId']);
Route::post('/merchants/users/{id}/update-permissions', [MerchantUsersController::class, 'updatePermissions']);
Route::post('/merchants/users/{id}/update-permissions-api-merchants', [MerchantUsersController::class, 'updatePermissionsForApiMerchants']);

Route::post('/merchants/requests', [RequestsController::class, 'register']);

Route::get('/stores', [StoresController::class, 'index']);
Route::get('/stores/{id}', [StoresController::class, 'show']);

Route::get('/counters', [CountersController::class, 'index']);

Route::get('/merchants', [MerchantsController::class, 'index']);
Route::post('/merchants/verify', [MerchantsController::class, 'verifyToken']);
Route::get('/merchants/{id}', [MerchantsController::class, 'show']);
