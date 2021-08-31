<?php

use App\Http\Controllers\ApiOnlineGateway\Merchants\MerchantsController as OnlineMerchantsController;
use Illuminate\Support\Facades\Route;


Route::prefix('merchants')
    ->group(function () {
        Route::get('/', [OnlineMerchantsController::class, 'index']);
        Route::get('tags', [OnlineMerchantsController::class, 'tags']);
    });

