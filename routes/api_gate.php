<?php

use App\Http\Controllers\ApiGate\Complaints\ComplaintsController;
use App\Http\Controllers\ApiGate\Conditions\ConditionsController;
use App\Http\Controllers\ApiGate\Merchants\AzoMerchantAccesses;
use App\Http\Controllers\ApiGate\Merchants\MerchantInfoController;
use App\Http\Controllers\ApiGate\Merchants\MerchantsController;
use App\Http\Controllers\ApiGate\Stores\StoresController;
use Illuminate\Support\Facades\Route;

//Core
Route::post('/complaints', [ComplaintsController::class, 'store']);

//Core
Route::get('/merchants/{merchant_id}/conditions/{condition_id}', [ConditionsController::class, 'getConditionByMerchantId']);
Route::get('/alifshop/company/{company_id}/conditions', [ConditionsController::class, 'getAlifshopConditionsByCompanyId']);
Route::get('/alifshop/merchants/{company_id}', [MerchantsController::class, 'getMerchantByCompanyId']);
Route::get('/merchants/{merchant_id}/stores/{store_id}', [StoresController::class, 'getStoreByMerchantId']);
Route::get('/merchants/users/{user_id}/by-user', [AzoMerchantAccesses::class, 'getByUserId']);
Route::get('/merchants/{id}', [MerchantsController::class, 'show']);

//service-docs
Route::get('/service-docs/{merchant_id}', [MerchantInfoController::class, 'getMerchantInfoByMerchantId']);

//Telegram BOT
Route::post('/merchants/verify', [MerchantsController::class, 'verifyToken']);

Route::get('credits/{tin}/get-merchant-by-tin', [MerchantsController::class, 'getMerchantByTinForCredits']);
