<?php

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
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
                    ->withoutMiddleware([GatewayAuthUser::class]);

                Route::get('tags', [OnlineMerchantsController::class, 'tags'])
                    ->withoutMiddleware([GatewayAuthUser::class]);

                Route::get('/{id}', [OnlineMerchantsController::class, 'show'])
                    ->withoutMiddleware([GatewayAuthUser::class]);
            });

        Route::prefix('stores')
            ->group(function () {
                Route::get('/', [StoresController::class, 'index'])
                    ->withoutMiddleware([GatewayAuthUser::class]);
            });

        Route::prefix('conditions')
            ->group(function () {
                Route::get('/', [ConditionsController::class, 'index'])
                    ->withoutMiddleware([GatewayAuthUser::class]);
            });
    });

