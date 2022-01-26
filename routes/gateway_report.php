<?php

use Illuminate\Support\Facades\Route;


Route::prefix('merchants')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\ApiReportGateway\Merchants\MerchantsController::class, 'index']);
    });

