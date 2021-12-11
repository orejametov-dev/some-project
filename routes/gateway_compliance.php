<?php

use Illuminate\Http\Request;
use App\Http\Controllers\ApiComplianceGateway\ProblemCases\ProblemCasesController;
use Illuminate\Support\Facades\Route;

Route::prefix('merchants/problem-cases')
    ->group(function () {
        Route::post('/', [ProblemCasesController::class, 'store']);
    });

Route::prefix('merchants')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\ApiComplianceGateway\Merchants\MerchantsController::class, 'index']);
    });

Route::prefix('stores')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\ApiComplianceGateway\Stores\StoresController::class, 'index']);
    });

