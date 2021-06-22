<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('merchants')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\ApiComplianceGateway\Merchants\MerchantsController::class, 'index']);
    });

Route::prefix('stores')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\ApiComplianceGateway\Stores\StoresController::class, 'index']);
    });

