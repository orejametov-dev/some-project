<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('merchants')
    ->group(function () {

        Route::get('/', [\App\Http\Controllers\ApiCreditsGateway\Merchants\MerchantsController::class, 'index']);
        Route::get('/group-legal-name', [\App\Http\Controllers\ApiCreditsGateway\Merchants\MerchantsController::class, 'indexSpecial']);
    });

Route::prefix('stores')
    ->group(function () {

        Route::get('/', [\App\Http\Controllers\ApiCreditsGateway\Stores\StoresController::class, 'index']);
    });
