<?php

use App\Http\Controllers\ApiOnlineGateway\Conditions\ConditionsController;
use App\Http\Controllers\ApiOnlineGateway\Merchants\MerchantsController as OnlineMerchantsController;
use App\Http\Controllers\ApiOnlineGateway\Stores\StoresController;
use Illuminate\Support\Facades\Route;


Route::prefix('merchants')
    ->group(function () {
        Route::get('/', [OnlineMerchantsController::class, 'index']);
    });

