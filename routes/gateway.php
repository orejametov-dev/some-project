<?php

use App\Http\Controllers\ApiGateway\Companies\CompaniesController;
use App\Http\Controllers\ApiGateway\Companies\CompanyUsersController;
use App\Http\Controllers\ApiGateway\AzoMerchants\ExtraServices\MerchantsController as ExtraMerchantsController;
use App\Http\Controllers\ApiGateway\ApiAlifshopMerchants\AlifshopMerchants\AlifshopMerchantsController;
use App\Http\Controllers\ApiGateway\ApiAlifshopMerchants\AlifshopMerchants\AlifshopMerchantAccessController;
use App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantsController;
use Illuminate\Support\Facades\Route;


//Companies
Route::prefix('companies')->group(function () {
    Route::get('/', [CompaniesController::class, 'index']);
    Route::get('/{id}', [CompaniesController::class, 'show']);
    Route::post('/', [CompaniesController::class, 'store']);
    Route::post('/special-store', [CompaniesController::class, 'storeSpecial']);
});

Route::prefix('companies/users')->group(function () {
    Route::get('/', [CompanyUsersController::class, 'index']);
});

//AlifshopMerchants
Route::prefix('alifshop-merchants')
    ->group(function () {
        Route::get('/' , [AlifshopMerchantsController::class, 'index']);
        Route::get('/{id}' , [AlifshopMerchantsController::class, 'show']);
        Route::post('/' , [AlifshopMerchantsController::class, 'store']);
        Route::match(['put' , 'patch'], '/{id}' , [AlifshopMerchantsController::class, 'update']);
        Route::post('/{id}/set-maintainer' , [AlifshopMerchantsController::class, 'setMaintainer']);
        Route::post('/{id}/upload-logo', [AlifshopMerchantsController::class, 'uploadLogo']);
        Route::post('/{id}/remove-logo', [AlifshopMerchantsController::class, 'removeLogo']);
        Route::match(['put', 'patch'], '/{id}/toggle', [AlifshopMerchantsController::class, 'toggle']);
        Route::post('/{id}/set-tags', [AlifshopMerchantsController::class, 'setTags']);
    });

Route::prefix('alifshop-merchants/users')
    ->group(function () {
        Route::get('/' , [AlifshopMerchantAccessController::class, 'index']);
        Route::get('/{id}' , [AlifshopMerchantAccessController::class, 'show']);
        Route::post('/' , [AlifshopMerchantAccessController::class, 'store']);
        Route::match(['put' , 'patch'], '/{id}' , [AlifshopMerchantAccessController::class, 'update']);
    });

//Azo-Merchants
Route::prefix('extra-services')->group(function () {
    Route::get('merchants', [ExtraMerchantsController::class, 'index']);
    Route::get('merchants/{merchant_id}/store', [ExtraMerchantsController::class, 'merchantStoreInfo']);
});

Route::prefix('merchants/requests')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantRequestsController::class, 'index']);
        Route::get('/{id}', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantRequestsController::class, 'show']);
        Route::match(['put', 'patch'], '/{id}', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantRequestsController::class, 'update']);
        Route::post('/{id}/upload', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantRequestsController::class, 'upload']);
        Route::delete('/{id}/delete-file', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantRequestsController::class, 'deleteFile']);
        Route::post('/{id}/allow', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantRequestsController::class, 'allow']);
        Route::post('/{id}/reject', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantRequestsController::class, 'reject']);
        Route::post('/', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantRequestsController::class, 'store']);
        Route::post('/set-engage/{id}', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantRequestsController::class, 'setEngage']);
        Route::get('counters/new', [App\Http\Controllers\ApiGateway\App\CountersController::class, 'merchantRequests']);
    });

Route::prefix('merchants/users')
    ->group(function () {
        Route::post('/', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\AzoMerchantAccessesController::class, 'store']);
        Route::get('/', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\AzoMerchantAccessesController::class, 'index']);
        Route::delete('/{id}', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\AzoMerchantAccessesController::class, 'destroy']);
        Route::get('/{id}', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\AzoMerchantAccessesController::class, 'show']);
        Route::match(['put', 'patch'], '/{id}', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\AzoMerchantAccessesController::class, 'update']);
        Route::post('/{id}/update-permissions-api-merchants', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\AzoMerchantAccessesController::class, 'updatePermissionsForApiMerchants']);
    });

Route::prefix('merchants/files')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantFilesController::class, 'index']);
        Route::post('/', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantFilesController::class, 'upload']);
        Route::delete('/{merchant_id}/{file_id}', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantFilesController::class, 'delete']);
    });

Route::prefix('merchants/info')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantInfoController::class, 'index']);
        Route::get('/{id}', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantInfoController::class, 'show']);
        Route::post('/', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantInfoController::class, 'store']);
        Route::post('/{id}', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantInfoController::class, 'update']);
        Route::post('/{id}/contract-trust', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantInfoController::class, 'getContractTrust']);
        Route::post('/{id}/contract', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantInfoController::class, 'getContract']);
    });

Route::prefix('merchants/problem-cases')
    ->group(function () {
        Route::get('/tags', [\App\Http\Controllers\ApiGateway\AzoMerchants\ProblemCases\ProblemCaseTagsController::class, 'index']);
        Route::get('/', [\App\Http\Controllers\ApiGateway\AzoMerchants\ProblemCases\ProblemCasesController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\ApiGateway\AzoMerchants\ProblemCases\ProblemCasesController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\ApiGateway\AzoMerchants\ProblemCases\ProblemCasesController::class, 'show']);
        Route::match(['put', 'patch'], '/{id}', [\App\Http\Controllers\ApiGateway\AzoMerchants\ProblemCases\ProblemCasesController::class, 'update']);
        Route::match(['put', 'patch'], '/{id}/attach-tags', [\App\Http\Controllers\ApiGateway\AzoMerchants\ProblemCases\ProblemCasesController::class, 'attachTags']);
        Route::match(['put', 'patch'], '/{id}/set-status', [\App\Http\Controllers\ApiGateway\AzoMerchants\ProblemCases\ProblemCasesController::class, 'setStatus']);
        Route::get('/{user_id}/consultant', [\App\Http\Controllers\ApiGateway\AzoMerchants\ProblemCases\ProblemCasesController::class, 'getProblemCasesOfMerchantUser']);
    });

Route::prefix('merchants/additional-agreements')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\AdditionalAgreementsController::class, 'index']);
        Route::post('/', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\AdditionalAgreementsController::class, 'store']);
        Route::put('/{id}', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\AdditionalAgreementsController::class, 'update']);
        Route::post('/{id}/documents', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\AdditionalAgreementsController::class, 'getAdditionalAgreementDoc']);
        Route::delete('/{id}', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\AdditionalAgreementsController::class, 'delete']);
    });

Route::prefix('merchants/tags')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantTagController::class, 'index']);
        Route::get('/{id}', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantTagController::class, 'show']);
        Route::post('/', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantTagController::class, 'store']);
        Route::post('set-tags', [MerchantsController::class, 'setTags']);
        Route::delete('/{id}', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantTagController::class, 'removeTag']);
    });

Route::prefix('stores')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\ApiGateway\AzoMerchants\Stores\StoresController::class, 'index']);
        Route::get('/{id}', [App\Http\Controllers\ApiGateway\AzoMerchants\Stores\StoresController::class, 'show']);
        Route::post('/', [App\Http\Controllers\ApiGateway\AzoMerchants\Stores\StoresController::class, 'store']);
        Route::match(['put', 'patch'], '/{id}/toggle', [App\Http\Controllers\ApiGateway\AzoMerchants\Stores\StoresController::class, 'toggle']);
        Route::match(['put', 'patch'], '/{id}', [App\Http\Controllers\ApiGateway\AzoMerchants\Stores\StoresController::class, 'update']);
    });

Route::prefix('notifications')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\ApiGateway\AzoMerchants\Stores\NotificationsController::class, 'index']);
        Route::get('/{id}', [\App\Http\Controllers\ApiGateway\AzoMerchants\Stores\NotificationsController::class, 'show']);
        Route::post('/', [\App\Http\Controllers\ApiGateway\AzoMerchants\Stores\NotificationsController::class, 'store']);
        Route::match(['put', 'patch'], '/{id}', [\App\Http\Controllers\ApiGateway\AzoMerchants\Stores\NotificationsController::class, 'update']);
        Route::delete( '/{id}', [\App\Http\Controllers\ApiGateway\AzoMerchants\Stores\NotificationsController::class, 'remove']);
    });

Route::prefix('app')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\ApiGateway\App\AppController::class, 'index']);
    });

Route::prefix('districts')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\ApiGateway\App\AppController::class, 'getDistricts']);
    });


Route::prefix('application-conditions')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\ApplicationConditionsController::class, 'index']);
        Route::get('/actives', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\ApplicationConditionsController::class, 'activeIndex']);
        Route::post('/', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\ApplicationConditionsController::class, 'store']);
        Route::match(['put', 'patch'], '/{id}', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\ApplicationConditionsController::class, 'update']);
        Route::post('/{id}/toggle', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\ApplicationConditionsController::class, 'toggle']);
        Route::delete('/{id}', [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\ApplicationConditionsController::class, 'delete']);
        Route::post('/{id}/toggle-posts' , [App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\ApplicationConditionsController::class , 'togglePosts']);
    });

Route::prefix('dashboard')
    ->group(function () {
        Route::get('/hot-merchants', [MerchantsController::class, 'hotMerchants']);
        Route::get('/merchant-trends', [App\Http\Controllers\ApiGateway\AzoMerchants\Dashboard\CreditsController::class, 'index']);
        Route::get('/merchant-trends/{merchant_id}', [App\Http\Controllers\ApiGateway\AzoMerchants\Dashboard\CreditsController::class, 'show']);
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
        Route::match(['put', 'patch'], '/{id}/toggle', [MerchantsController::class, 'toggle']);
        Route::post('/{id}/set-tags', [MerchantsController::class, 'setTags']);

        Route::match(['put', 'patch'], '/{id}/update-modules', [MerchantsController::class, 'updateModules']);
    });

