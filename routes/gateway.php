<?php

use App\Http\Controllers\ApiGateway\ExtraServices\MerchantsController as ExtraMerchantsController;
use App\Http\Controllers\ApiGateway\Merchants\MerchantsController;
use Illuminate\Support\Facades\Route;


Route::prefix('extra-services')->group(function () {
    Route::get('merchants', [ExtraMerchantsController::class, 'index']);
    Route::get('merchants/{merchant_id}/store', [ExtraMerchantsController::class, 'merchantStoreInfo']);
});

Route::prefix('merchants/requests')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\ApiGateway\Merchants\MerchantRequestsController::class, 'index']);
        Route::get('/{id}', [App\Http\Controllers\ApiGateway\Merchants\MerchantRequestsController::class, 'show']);
        Route::match(['put', 'patch'], '/{id}', [App\Http\Controllers\ApiGateway\Merchants\MerchantRequestsController::class, 'update']);
        Route::post('/{id}/upload', [App\Http\Controllers\ApiGateway\Merchants\MerchantRequestsController::class, 'upload']);
        Route::delete('/{id}/delete-file', [App\Http\Controllers\ApiGateway\Merchants\MerchantRequestsController::class, 'deleteFile']);
        Route::post('/{id}/allow', [App\Http\Controllers\ApiGateway\Merchants\MerchantRequestsController::class, 'allow']);
        Route::post('/{id}/reject', [App\Http\Controllers\ApiGateway\Merchants\MerchantRequestsController::class, 'reject']);
        Route::post('/', [App\Http\Controllers\ApiGateway\Merchants\MerchantRequestsController::class, 'store']);
        Route::post('/set-engage/{id}', [App\Http\Controllers\ApiGateway\Merchants\MerchantRequestsController::class, 'setEngage']);
        Route::get('counters/new', [App\Http\Controllers\ApiGateway\App\CountersController::class, 'merchantRequests']);
    });

Route::prefix('merchants/users')
    ->group(function () {
        Route::post('/', [App\Http\Controllers\ApiGateway\Merchants\MerchantUserController::class, 'store']);
        Route::get('/', [App\Http\Controllers\ApiGateway\Merchants\MerchantUserController::class, 'index']);
        Route::delete('/{id}', [App\Http\Controllers\ApiGateway\Merchants\MerchantUserController::class, 'destroy']);
        Route::get('/{id}', [App\Http\Controllers\ApiGateway\Merchants\MerchantUserController::class, 'show']);
        Route::match(['put', 'patch'], '/{id}', [App\Http\Controllers\ApiGateway\Merchants\MerchantUserController::class, 'update']);
        Route::post('/{id}/update-permissions-api-merchants', [App\Http\Controllers\ApiGateway\Merchants\MerchantUserController::class, 'updatePermissionsForApiMerchants']);
    });

Route::prefix('merchants/files')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\ApiGateway\Merchants\MerchantFilesController::class, 'index']);
        Route::post('/', [App\Http\Controllers\ApiGateway\Merchants\MerchantFilesController::class, 'upload']);
        Route::delete('/{merchant_id}/{file_id}', [App\Http\Controllers\ApiGateway\Merchants\MerchantFilesController::class, 'delete']);
    });

Route::prefix('merchants/info')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\ApiGateway\Merchants\MerchantInfoController::class, 'index']);
        Route::get('/{id}', [App\Http\Controllers\ApiGateway\Merchants\MerchantInfoController::class, 'show']);
        Route::post('/', [App\Http\Controllers\ApiGateway\Merchants\MerchantInfoController::class, 'store']);
        Route::post('/{id}', [App\Http\Controllers\ApiGateway\Merchants\MerchantInfoController::class, 'update']);
        Route::post('/{id}/contract-trust', [App\Http\Controllers\ApiGateway\Merchants\MerchantInfoController::class, 'getContractTrust']);
        Route::post('/{id}/contract', [App\Http\Controllers\ApiGateway\Merchants\MerchantInfoController::class, 'getContract']);
    });

Route::prefix('merchants/problem-cases')
    ->group(function () {
        Route::get('/tags', [\App\Http\Controllers\ApiGateway\ProblemCases\ProblemCaseTagsController::class, 'index']);
        Route::get('/', [\App\Http\Controllers\ApiGateway\ProblemCases\ProblemCasesController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\ApiGateway\ProblemCases\ProblemCasesController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\ApiGateway\ProblemCases\ProblemCasesController::class, 'show']);
        Route::match(['put', 'patch'], '/{id}', [\App\Http\Controllers\ApiGateway\ProblemCases\ProblemCasesController::class, 'update']);
        Route::match(['put', 'patch'], '/{id}/attach-tags', [\App\Http\Controllers\ApiGateway\ProblemCases\ProblemCasesController::class, 'attachTags']);
        Route::match(['put', 'patch'], '/{id}/set-status', [\App\Http\Controllers\ApiGateway\ProblemCases\ProblemCasesController::class, 'setStatus']);

    });

Route::prefix('merchants/additional-agreements')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\ApiGateway\Merchants\AdditionalAgreementsController::class, 'index']);
        Route::post('/', [App\Http\Controllers\ApiGateway\Merchants\AdditionalAgreementsController::class, 'store']);
        Route::put('/{id}', [App\Http\Controllers\ApiGateway\Merchants\AdditionalAgreementsController::class, 'update']);
        Route::post('/{id}/documents', [App\Http\Controllers\ApiGateway\Merchants\AdditionalAgreementsController::class, 'getAdditionalAgreementDoc']);
        Route::delete('/{id}', [App\Http\Controllers\ApiGateway\Merchants\AdditionalAgreementsController::class, 'delete']);
    });

Route::prefix('merchants/tags')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\ApiGateway\Merchants\MerchantTagController::class, 'index']);
        Route::get('/{id}', [App\Http\Controllers\ApiGateway\Merchants\MerchantTagController::class, 'show']);
        Route::post('/', [App\Http\Controllers\ApiGateway\Merchants\MerchantTagController::class, 'store']);
        Route::post('set-tags', [MerchantsController::class, 'setTags']);
        Route::delete('/{id}', [App\Http\Controllers\ApiGateway\Merchants\MerchantTagController::class, 'removeTag']);
    });

        Route::prefix('stores')
            ->group(function () {
                Route::get('/', [App\Http\Controllers\ApiGateway\Stores\StoresController::class, 'index']);
                Route::get('/{id}', [App\Http\Controllers\ApiGateway\Stores\StoresController::class, 'show']);
                Route::post('/', [App\Http\Controllers\ApiGateway\Stores\StoresController::class, 'store']);
                Route::match(['put', 'patch'],'/{id}/toggle', [App\Http\Controllers\ApiGateway\Stores\StoresController::class, 'toggle']);
                Route::match(['put', 'patch'], '/{id}', [App\Http\Controllers\ApiGateway\Stores\StoresController::class, 'update']);
            });

Route::prefix('notifications')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\ApiGateway\Stores\NotificationsController::class, 'index']);
        Route::get('/{id}', [\App\Http\Controllers\ApiGateway\Stores\NotificationsController::class, 'show']);
        Route::post('/', [\App\Http\Controllers\ApiGateway\Stores\NotificationsController::class, 'store']);
    });

Route::prefix('app')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\ApiGateway\App\AppController::class, 'index']);
    });

Route::prefix('application-conditions')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\ApiGateway\Merchants\ApplicationConditionsController::class, 'index']);
        Route::get('/actives', [App\Http\Controllers\ApiGateway\Merchants\ApplicationConditionsController::class, 'activeIndex']);
        Route::post('/', [App\Http\Controllers\ApiGateway\Merchants\ApplicationConditionsController::class, 'store']);
        Route::match(['put', 'patch'], '/{id}', [App\Http\Controllers\ApiGateway\Merchants\ApplicationConditionsController::class, 'update']);
        Route::post('/{id}/toggle', [App\Http\Controllers\ApiGateway\Merchants\ApplicationConditionsController::class, 'toggle']);
        Route::delete('/{id}', [App\Http\Controllers\ApiGateway\Merchants\ApplicationConditionsController::class, 'delete']);
    });

Route::prefix('dashboard')
    ->group(function () {
        Route::get('/hot-merchants', [MerchantsController::class, 'hotMerchants']);
        Route::get('/merchant-trends', [App\Http\Controllers\ApiGateway\Dashboard\CreditsController::class, 'index']);
        Route::get('/merchant-trends/{merchant_id}', [App\Http\Controllers\ApiGateway\Dashboard\CreditsController::class, 'show']);
    });

Route::prefix('merchants')
    ->group(function () {
        Route::get('/', [MerchantsController::class, 'index']);
        Route::get('/{id}', [MerchantsController::class, 'show']);
        Route::post('/', [MerchantsController::class, 'store']);
        Route::match(['put', 'patch'], '/{id}', [MerchantsController::class, 'update']);

                Route::post('/{id}/set-main-store', [MerchantsController::class, 'setMainStore']);
                Route::post('/{id}/update-chat-id', [MerchantsController::class, 'updateChatId']);
                Route::post('/{id}/upload-logo', [MerchantsController::class, 'uploadLogo']);
                Route::post('/{id}/remove-logo', [MerchantsController::class, 'removeLogo']);
                Route::post('/{id}/set-responsible-user', [MerchantsController::class, 'setResponsibleUser']);
                Route::match(['put', 'patch'],'/{id}/toggle', [MerchantsController::class, 'toggle']);

        Route::match(['put', 'patch'], '/{id}/update-modules', [MerchantsController::class, 'updateModules']);
    });
