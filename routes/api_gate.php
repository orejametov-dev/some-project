<?php

use App\Http\Controllers\ApiGate\App\CountersController;
use App\Http\Controllers\ApiGate\Conditions\ConditionsController;
use App\Http\Controllers\ApiGate\Merchants\MerchantsController;
use App\Http\Controllers\ApiGate\Merchants\MerchantUsersController;
use App\Http\Controllers\ApiGate\Merchants\RequestsController;
use App\Http\Controllers\ApiGate\Stores\StoresController;
use Illuminate\Support\Facades\Route;

//Core
Route::get('/merchants/{merchant_id}/conditions/{condition_id}', [ConditionsController::class, 'getConditionByMerchantId']);
Route::get('/merchants/{merchant_id}/stores/{store_id}', [StoresController::class, 'getStoreByMerchantId']);
Route::get('/merchants/users/{user_id}/by-user', [MerchantUsersController::class, 'getByUserId']);
Route::get('/merchants/{id}', [MerchantsController::class, 'show']);

//Telegram BOT
Route::post('/merchants/verify', [MerchantsController::class, 'verifyToken']);

Route::get('credits/merchants/{id}', [MerchantsController::class, 'getMerchantByIdForCredits']);
