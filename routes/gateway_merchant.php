<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('merchants/problem-cases')
    ->group(function () {
        Route::get('/statuses', [\App\Http\Controllers\ApiMerchantGateway\ProblemCases\ProblemCasesController::class, 'getStatuses']);
        Route::get('/new-problem-cases-counter', [\App\Http\Controllers\ApiMerchantGateway\ProblemCases\ProblemCasesController::class, 'getNewProblemCasesCounter']);

        Route::get('/', [\App\Http\Controllers\ApiMerchantGateway\ProblemCases\ProblemCasesController::class, 'index']);
        Route::get('/{id}', [\App\Http\Controllers\ApiMerchantGateway\ProblemCases\ProblemCasesController::class, 'show']);
        Route::match(['put', 'patch'], '/{id}/set-status', [\App\Http\Controllers\ApiMerchantGateway\ProblemCases\ProblemCasesController::class, 'setStatus']);
        Route::match(['put', 'patch'], '/{id}/set-engage', [\App\Http\Controllers\ApiMerchantGateway\ProblemCases\ProblemCasesController::class, 'setEngage']);
    });

Route::prefix('merchants/requests')
    ->group(function () {
        Route::get('/app', [\App\Http\Controllers\ApiMerchantGateway\Merchants\MerchantRequestsController::class, 'app'])->withoutMiddleware(['gateway-access', 'gateway-auth-user']);
        Route::get('/{id}', [\App\Http\Controllers\ApiMerchantGateway\Merchants\MerchantRequestsController::class, 'show'])->withoutMiddleware(['gateway-access', 'gateway-auth-user']);
        Route::post('/', [\App\Http\Controllers\ApiMerchantGateway\Merchants\MerchantRequestsController::class, 'store'])->withoutMiddleware(['gateway-access', 'gateway-auth-user']);
        Route::put('/{id}', [\App\Http\Controllers\ApiMerchantGateway\Merchants\MerchantRequestsController::class, 'update'])->withoutMiddleware(['gateway-access', 'gateway-auth-user']);
        Route::post('/{id}/upload-files', [\App\Http\Controllers\ApiMerchantGateway\Merchants\MerchantRequestsController::class, 'upload'])->withoutMiddleware(['gateway-access', 'gateway-auth-user']);
    });
