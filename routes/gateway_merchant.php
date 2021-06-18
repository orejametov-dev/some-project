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
        Route::get('/{token}', [\App\Http\Controllers\ApiMerchantGateway\Merchants\MerchantRequestsController::class, 'show'])->withoutMiddleware(['gateway-access', 'gateway-auth-user']);
        Route::post('/store-main', [\App\Http\Controllers\ApiMerchantGateway\Merchants\MerchantRequestsController::class, 'storeMain'])->withoutMiddleware(['gateway-access', 'gateway-auth-user']);
        Route::post('/store-documents', [\App\Http\Controllers\ApiMerchantGateway\Merchants\MerchantRequestsController::class, 'storeDocuments'])->withoutMiddleware(['gateway-access', 'gateway-auth-user']);
        Route::post('/upload-files', [\App\Http\Controllers\ApiMerchantGateway\Merchants\MerchantRequestsController::class, 'upload'])->withoutMiddleware(['gateway-access', 'gateway-auth-user']);
    });

Route::prefix('merchants/tags')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\ApiMerchantGateway\Merchants\MerchantTagsController::class, 'index'] )->withoutMiddleware(['gateway-access', 'gateway-auth-user']);
    });
