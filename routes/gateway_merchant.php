<?php

declare(strict_types=1);

use Alifuz\Utils\Gateway\Middlewares\GatewayAuthMiddleware;
use Alifuz\Utils\Gateway\Middlewares\GatewayMiddleware;
use App\Http\Controllers\ApiMerchantGateway\Merchants\MerchantsController;
use App\Http\Middleware\AzoMerchantAccessMiddleware;
use Illuminate\Support\Facades\Route;

//azo-merchant
Route::prefix('application-conditions')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\ApiMerchantGateway\Merchants\ApplicationConditionsController::class, 'index']);
    });

Route::prefix('merchants/problem-cases')
    ->group(function () {
        Route::get('/statuses', [\App\Http\Controllers\ApiMerchantGateway\ProblemCases\ProblemCasesController::class, 'getStatuses']);
        Route::get('/new-problem-cases-counter', [\App\Http\Controllers\ApiMerchantGateway\ProblemCases\ProblemCasesController::class, 'getNewProblemCasesCounter']);

        Route::get('/', [\App\Http\Controllers\ApiMerchantGateway\ProblemCases\ProblemCasesController::class, 'index']);
        Route::get('/{id}', [\App\Http\Controllers\ApiMerchantGateway\ProblemCases\ProblemCasesController::class, 'show']);
        Route::match(['put', 'patch'], '/{id}/set-status', [\App\Http\Controllers\ApiMerchantGateway\ProblemCases\ProblemCasesController::class, 'setStatus']);
        Route::match(['put', 'patch'], '/{id}/set-engage', [\App\Http\Controllers\ApiMerchantGateway\ProblemCases\ProblemCasesController::class, 'setEngage']);
        Route::match(['put', 'patch'], '/{id}/set-comment', [\App\Http\Controllers\ApiMerchantGateway\ProblemCases\ProblemCasesController::class, 'setCommentFromMerchant']);
    });

Route::prefix('merchants/requests')
    ->group(function () {
        Route::get('/app', [\App\Http\Controllers\ApiMerchantGateway\Merchants\MerchantRequestsController::class, 'app'])
            ->withoutMiddleware([GatewayMiddleware::class, GatewayAuthMiddleware::class, AzoMerchantAccessMiddleware::class]);
        Route::get('/districts', [\App\Http\Controllers\ApiMerchantGateway\Merchants\MerchantRequestsController::class, 'getDistricts'])
            ->withoutMiddleware([GatewayMiddleware::class, GatewayAuthMiddleware::class, AzoMerchantAccessMiddleware::class]);
        Route::post('/store-main', [\App\Http\Controllers\ApiMerchantGateway\Merchants\MerchantRequestsController::class, 'storeMain'])
            ->withoutMiddleware([GatewayMiddleware::class, GatewayAuthMiddleware::class, AzoMerchantAccessMiddleware::class]);
    });

Route::prefix('merchants/tags')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\ApiMerchantGateway\Merchants\MerchantTagsController::class, 'index'])
            ->withoutMiddleware([GatewayMiddleware::class, GatewayAuthMiddleware::class, AzoMerchantAccessMiddleware::class]);
    });

Route::prefix('merchants/users')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\ApiMerchantGateway\Merchants\AzoMerchantAccessesController::class, 'index']);
        Route::get('/check-to-active-merchant', [\App\Http\Controllers\ApiMerchantGateway\MerchantAccess\MerchantAccessController::class, 'checkToActiveMerchant'])
            ->withoutMiddleware([GatewayMiddleware::class, GatewayAuthMiddleware::class]);
        Route::match(['put', 'patch'], '/{id}', [App\Http\Controllers\ApiMerchantGateway\Merchants\AzoMerchantAccessesController::class, 'update']);
        Route::post('/request-store', [App\Http\Controllers\ApiMerchantGateway\Merchants\AzoMerchantAccessesController::class, 'requestStore']);
        Route::post('/', [App\Http\Controllers\ApiMerchantGateway\Merchants\AzoMerchantAccessesController::class, 'store']);
        Route::delete('/{id}', [App\Http\Controllers\ApiMerchantGateway\Merchants\AzoMerchantAccessesController::class, 'destroy']);
    });

Route::prefix('notifications')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\ApiMerchantGateway\Notifications\NotificationsController::class, 'index']);
        Route::get('/counter', [\App\Http\Controllers\ApiMerchantGateway\Notifications\NotificationsController::class, 'getCounter']);
    });

Route::prefix('stores')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\ApiMerchantGateway\Stores\StoresController::class, 'index']);
    });

Route::prefix('merchants')
    ->group(function () {
        Route::get('/get-with-relations', [MerchantsController::class, 'getMerchantDetailsWithRelations']);
    });
