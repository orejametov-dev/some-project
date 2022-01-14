<?php

use App\Http\Controllers\ApiGate\Conditions\ConditionsController;
use App\Http\Controllers\ApiGate\Merchants\MerchantsController;
use App\Http\Controllers\ApiGate\Merchants\AzoMerchantAccesses;
use App\Http\Controllers\ApiGate\Stores\StoresController;
use App\Http\Controllers\ApiGate\AlifshopMerchants\AlifshopMerchantAccesses;
use Illuminate\Support\Facades\Route;

//Core
Route::get('/merchants/{merchant_id}/conditions/{condition_id}', [ConditionsController::class, 'getConditionByMerchantId']);
Route::get('/alifshop/company/{company_id}/conditions', [ConditionsController::class, 'getAlifshopConditionsByCompanyId']);
Route::get('/merchants/{merchant_id}/stores/{store_id}', [StoresController::class, 'getStoreByMerchantId']);
Route::get('/merchants/users/{user_id}/by-user', [AzoMerchantAccesses::class, 'getByUserId']);
Route::get('/alifshop-merchants/users/{user_id}/by-user', [AlifshopMerchantAccesses::class, 'getByUserId']);
Route::get('/merchants/{id}', [MerchantsController::class, 'show']);

//Telegram BOT
Route::post('/merchants/verify', [MerchantsController::class, 'verifyToken']);

Route::get('credits/{tin}/get-merchant-by-tin', [MerchantsController::class, 'getMerchantByTinForCredits']);

Route::post('/problem-cases', [\App\Http\Controllers\ApiGate\ProblemCase\ProblemCasesController::class, 'store']);

