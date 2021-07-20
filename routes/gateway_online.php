<?php

use App\Http\Controllers\ApiGateway\Online\MerchantsController as OnlineMerchantsController;
use Illuminate\Support\Facades\Route;


Route::prefix('online')
    ->group(function () {
        Route::prefix('merchants')
            ->group(function () {
                Route::get('/', [OnlineMerchantsController::class, 'index']);
                Route::get('tags', [OnlineMerchantsController::class, 'tags']);
            });
    });
