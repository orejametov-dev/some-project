<?php

declare(strict_types=1);

use App\Http\Controllers\ApiComplianceGateway\ProblemCases\ProblemCasesController;
use App\Http\Controllers\ApiComplianceGateway\ProblemCases\ProblemCaseTagsController;
use Illuminate\Support\Facades\Route;

Route::prefix('merchants/problem-cases')
    ->group(function () {
        Route::post('/', [ProblemCasesController::class, 'store']);
        Route::match(['put', 'patch'], '/{id}/attach-tags', [ProblemCasesController::class, 'attachTags']);
        Route::get('/tags', [ProblemCaseTagsController::class, 'index']);
    });

Route::prefix('merchants')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\ApiComplianceGateway\Merchants\MerchantsController::class, 'index']);
    });

Route::prefix('stores')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\ApiComplianceGateway\Stores\StoresController::class, 'index']);
    });
