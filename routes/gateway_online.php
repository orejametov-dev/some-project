<?php

use Alifuz\Utils\Gateway\Middlewares\GatewayAuthMiddleware;
use App\Http\Controllers\ApiOnlineGateway\Conditions\ConditionsController;
use App\Http\Controllers\ApiOnlineGateway\Merchants\MerchantsController as OnlineMerchantsController;
use App\Http\Controllers\ApiOnlineGateway\Stores\StoresController;
use Illuminate\Support\Facades\Route;


Route::prefix('merchants')
    ->group(function () {
        Route::get('/', [OnlineMerchantsController::class, 'index']);
        Route::get('tags', [OnlineMerchantsController::class, 'tags']);
    });

Route::prefix('public')
    ->group(function () {
        Route::prefix('merchants')
            ->group(function () {
                Route::get('/', [OnlineMerchantsController::class, 'index'])
                    ->withoutMiddleware([GatewayAuthMiddleware::class]);

                Route::get('tags', [OnlineMerchantsController::class, 'tags'])
                    ->withoutMiddleware([GatewayAuthMiddleware::class]);

                Route::get('/{id}', [OnlineMerchantsController::class, 'show'])
                    ->withoutMiddleware([GatewayAuthMiddleware::class]);
            });

        Route::prefix('stores')
            ->group(function () {
                Route::get('/', [StoresController::class, 'index'])
                    ->withoutMiddleware([GatewayAuthMiddleware::class]);
            });

        Route::prefix('conditions')
            ->group(function () {
                Route::get('/', [ConditionsController::class, 'index'])
                    ->withoutMiddleware([GatewayAuthMiddleware::class]);
            });
    });

