<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('merchants/problem-cases')
    ->group(function () {
        Route::get('/statuses', [\App\Http\Controllers\ApiMerchantGateway\ProblemCases\ProblemCasesController::class, 'getStatuses']);
        Route::get('/', [\App\Http\Controllers\ApiMerchantGateway\ProblemCases\ProblemCasesController::class, 'index']);
        Route::get('/{id}', [\App\Http\Controllers\ApiMerchantGateway\ProblemCases\ProblemCasesController::class, 'show']);
        Route::match(['put', 'patch'], '/{id}/set-status', [\App\Http\Controllers\ApiMerchantGateway\ProblemCases\ProblemCasesController::class, 'setStatus']);
        Route::match(['put', 'patch'], '/{id}/set-engage', [\App\Http\Controllers\ApiMerchantGateway\ProblemCases\ProblemCasesController::class, 'setEngage']);
    });
