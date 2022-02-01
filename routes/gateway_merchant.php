<?php

use Alifuz\Utils\Gateway\Middlewares\GatewayAuthMiddleware;
use App\Http\Controllers\ApiMerchantGateway\Merchants\MerchantsController;
use App\Http\Middleware\GatewayAccessMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//azo-merchant
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('merchants/app', [\App\Http\Controllers\ApiMerchantGateway\App\AppController::class, 'index']);

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
        Route::get('/app', [\App\Http\Controllers\ApiMerchantGateway\Merchants\MerchantRequestsController::class, 'app'])->withoutMiddleware([GatewayAccessMiddleware::class, GatewayAuthMiddleware::class]);
        Route::get('/districts', [\App\Http\Controllers\ApiMerchantGateway\Merchants\MerchantRequestsController::class, 'getDistricts'])->withoutMiddleware([GatewayAccessMiddleware::class, GatewayAuthMiddleware::class]);
        Route::get('/{token}', [\App\Http\Controllers\ApiMerchantGateway\Merchants\MerchantRequestsController::class, 'show'])->withoutMiddleware([GatewayAccessMiddleware::class, GatewayAuthMiddleware::class]);
        Route::post('/store-main', [\App\Http\Controllers\ApiMerchantGateway\Merchants\MerchantRequestsController::class, 'storeMain'])->withoutMiddleware([GatewayAccessMiddleware::class, GatewayAuthMiddleware::class]);
        Route::post('/store-documents', [\App\Http\Controllers\ApiMerchantGateway\Merchants\MerchantRequestsController::class, 'storeDocuments'])->withoutMiddleware([GatewayAccessMiddleware::class, GatewayAuthMiddleware::class]);
        Route::post('/upload-files', [\App\Http\Controllers\ApiMerchantGateway\Merchants\MerchantRequestsController::class, 'upload'])->withoutMiddleware([GatewayAccessMiddleware::class, GatewayAuthMiddleware::class]);
        Route::delete('/delete-files/{file_id}', [\App\Http\Controllers\ApiMerchantGateway\Merchants\MerchantRequestsController::class, 'deleteFile'])->withoutMiddleware([GatewayAccessMiddleware::class, GatewayAuthMiddleware::class]);
    });

Route::prefix('merchants/tags')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\ApiMerchantGateway\Merchants\MerchantTagsController::class, 'index'])->withoutMiddleware(['gateway-access', 'gateway-auth-user']);
    });

Route::prefix('merchants/users')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\ApiMerchantGateway\Merchants\AzoMerchantAccessesController::class, 'index']);
        Route::get('/{id}', [App\Http\Controllers\ApiMerchantGateway\Merchants\AzoMerchantAccessesController::class, 'show']);
        Route::match(['put', 'patch'], '/{id}', [App\Http\Controllers\ApiMerchantGateway\Merchants\AzoMerchantAccessesController::class, 'update']);
        Route::post('/request-store' , [App\Http\Controllers\ApiMerchantGateway\Merchants\AzoMerchantAccessesController::class , 'requestStore']);
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
        Route::get('/', [MerchantsController::class, 'index']);
        Route::get('/get-with-relations', [MerchantsController::class, 'getMerchantDetailsWithRelations']);
    });
