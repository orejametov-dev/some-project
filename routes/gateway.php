<?php

declare(strict_types=1);

use App\Http\Controllers\ApiGateway\App\AppController;
use App\Http\Controllers\ApiGateway\App\CountersController;
use App\Http\Controllers\ApiGateway\AzoMerchants\Comments\CommentsController;
use App\Http\Controllers\ApiGateway\AzoMerchants\Complaints\ComplaintsController;
use App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\AdditionalAgreementsController;
use App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\ApplicationConditionsController;
use App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\AzoMerchantAccessesController;
use App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantFilesController;
use App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantInfoController;
use App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantRequestsController;
use App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantsController;
use App\Http\Controllers\ApiGateway\AzoMerchants\Merchants\MerchantTagController;
use App\Http\Controllers\ApiGateway\AzoMerchants\ProblemCases\ProblemCasesController;
use App\Http\Controllers\ApiGateway\AzoMerchants\ProblemCases\ProblemCaseTagsController;
use App\Http\Controllers\ApiGateway\AzoMerchants\Stores\NotificationsController;
use App\Http\Controllers\ApiGateway\AzoMerchants\Stores\StoresController;
use Illuminate\Support\Facades\Route;

Route::prefix('complaints')->group(function () {
    Route::get('/', [ComplaintsController::class, 'index']);
});

Route::prefix('comments')->group(function () {
    Route::get('/', [CommentsController::class, 'index']);
});

//Azo-Merchants

Route::prefix('merchants/requests')
    ->group(function () {
        Route::get('/', [MerchantRequestsController::class, 'index']);
        Route::post('/', [MerchantRequestsController::class, 'store']);
        Route::get('/{id}', [MerchantRequestsController::class, 'show']);
        Route::match(['put', 'patch'], '/{id}', [MerchantRequestsController::class, 'update']);
        Route::match(['put', 'patch'], '/{id}/store-documents', [MerchantRequestsController::class, 'storeDocuments']);
        Route::post('/{id}/upload', [MerchantRequestsController::class, 'upload']);
        Route::delete('/{id}/delete-file', [MerchantRequestsController::class, 'deleteFile']);
        Route::post('/{id}/allow', [MerchantRequestsController::class, 'allow']);
        Route::post('/{id}/reject', [MerchantRequestsController::class, 'reject']);
        Route::match(['put', 'patch'], '/{id}/on-boarding', [MerchantRequestsController::class, 'setOnBoarding']);
        Route::post('/set-engage/{id}', [MerchantRequestsController::class, 'setEngage']);
        Route::get('counters/new', [CountersController::class, 'merchantRequests']);
    });

Route::prefix('merchants/users')
    ->group(function () {
        Route::post('/', [AzoMerchantAccessesController::class, 'store']);
        Route::get('/', [AzoMerchantAccessesController::class, 'index']);
        Route::delete('/{id}', [AzoMerchantAccessesController::class, 'destroy']);
        Route::get('/{id}', [AzoMerchantAccessesController::class, 'show']);
        Route::match(['put', 'patch'], '/{id}', [AzoMerchantAccessesController::class, 'update']);
        Route::post('/{id}/update-permissions-api-merchants', [AzoMerchantAccessesController::class, 'updatePermissionsForApiMerchants']);
    });

Route::prefix('merchants/files')
    ->group(function () {
        Route::get('/', [MerchantFilesController::class, 'index']);
        Route::post('/', [MerchantFilesController::class, 'upload']);
        Route::delete('/{merchant_id}/{file_id}', [MerchantFilesController::class, 'delete']);
    });

Route::prefix('merchants/info')
    ->group(function () {
        Route::get('/', [MerchantInfoController::class, 'index']);
        Route::get('/{id}', [MerchantInfoController::class, 'show']);
        Route::post('/', [MerchantInfoController::class, 'store']);
        Route::post('/{id}', [MerchantInfoController::class, 'update']);
        Route::post('/{id}/contract-trust', [MerchantInfoController::class, 'getContractTrust']);
        Route::post('/{id}/contract-procuration', [MerchantInfoController::class, 'getContractProcuration']);
        Route::post('/{id}/contract', [MerchantInfoController::class, 'getContract']);
    });

Route::prefix('merchants/problem-cases')
    ->group(function () {
        Route::get('/tags', [ProblemCaseTagsController::class, 'index']);
        Route::get('/', [ProblemCasesController::class, 'index']);
        Route::get('/{id}', [ProblemCasesController::class, 'show']);
        Route::match(['put', 'patch'], '/{id}', [ProblemCasesController::class, 'update']);
        Route::match(['put', 'patch'], '/{id}/attach-tags', [ProblemCasesController::class, 'attachTags']);
        Route::match(['put', 'patch'], '/{id}/set-status', [ProblemCasesController::class, 'setStatus']);
        Route::match(['put', 'patch'], '/{id}/set-assigned', [ProblemCasesController::class, 'setAssigned']);
        Route::match(['put', 'patch'], '/{id}/manager-comment', [ProblemCasesController::class, 'setManagerComment']);
        Route::match(['put', 'patch'], '/{id}/merchant-comment', [ProblemCasesController::class, 'setMerchantComment']);
        Route::get('/{user_id}/consultant', [ProblemCasesController::class, 'getProblemCasesOfMerchantUser']);
    });

Route::prefix('merchants/additional-agreements')
    ->group(function () {
        Route::get('/', [AdditionalAgreementsController::class, 'index']);
        Route::post('/', [AdditionalAgreementsController::class, 'store']);
        Route::put('/{id}', [AdditionalAgreementsController::class, 'update']);
        Route::post('/{id}/documents', [AdditionalAgreementsController::class, 'getAdditionalAgreementDoc']);
        Route::delete('/{id}', [AdditionalAgreementsController::class, 'delete']);
    });

Route::prefix('merchants/tags')
    ->group(function () {
        Route::get('/', [MerchantTagController::class, 'index']);
        Route::get('/{id}', [MerchantTagController::class, 'show']);
        Route::post('/', [MerchantTagController::class, 'store']);
        Route::post('set-tags', [MerchantsController::class, 'setTags']);
        Route::delete('/{id}', [MerchantTagController::class, 'removeTag']);
    });

Route::prefix('stores')
    ->group(function () {
        Route::get('/', [StoresController::class, 'index']);
        Route::get('/{id}', [StoresController::class, 'show']);
        Route::get('/{id}/conditions', [StoresController::class, 'getConditions']);
        Route::post('/', [StoresController::class, 'store']);
        Route::match(['put', 'patch'], '/{id}/toggle', [StoresController::class, 'toggle']);
        Route::match(['put', 'patch'], '/{id}', [StoresController::class, 'update']);
        Route::match(['put', 'patch'], '/{id}/attach-azo', [StoresController::class, 'attachAzo']);
        Route::match(['put', 'patch'], '/{id}/set-type-register', [StoresController::class, 'setTypeRegister']);
    });

Route::prefix('notifications')
    ->group(function () {
        Route::get('/', [NotificationsController::class, 'index']);
        Route::get('/{id}', [NotificationsController::class, 'show']);
        Route::post('/', [NotificationsController::class, 'store']);
        Route::match(['put', 'patch'], '/{id}', [NotificationsController::class, 'update']);
        Route::delete('/{id}', [NotificationsController::class, 'remove']);
    });

Route::prefix('app')
    ->group(function () {
        Route::get('/', [AppController::class, 'index']);
    });

Route::prefix('districts')
    ->group(function () {
        Route::get('/', [AppController::class, 'getDistricts']);
    });

Route::prefix('application-conditions')
    ->group(function () {
        Route::get('/', [ApplicationConditionsController::class, 'index']);
        Route::post('/', [ApplicationConditionsController::class, 'store']);
        Route::post('/special-store', [ApplicationConditionsController::class, 'storeSpecial']);
        Route::post('/mass-store', [ApplicationConditionsController::class, 'massStore']);
        Route::post('/mass-special-store', [ApplicationConditionsController::class, 'massSpecialStore']);
        Route::match(['put', 'patch'], '/{id}', [ApplicationConditionsController::class, 'update']);
        Route::post('/{id}/toggle', [ApplicationConditionsController::class, 'toggle']);
        Route::delete('/{id}', [ApplicationConditionsController::class, 'delete']);
        Route::post('/{id}/toggle-posts', [ApplicationConditionsController::class, 'togglePosts']);
    });

Route::prefix('dashboard')
    ->group(function () {
        Route::get('/hot-merchants', [MerchantsController::class, 'hotMerchants']);
    });

Route::prefix('merchants')
    ->group(function () {
        Route::get('/', [MerchantsController::class, 'index']);
        Route::get('/{id}', [MerchantsController::class, 'show']);
        Route::post('/', [MerchantsController::class, 'store']);
        Route::match(['put', 'patch'], '/{id}', [MerchantsController::class, 'update']);

        Route::post('/{id}/set-main-store', [MerchantsController::class, 'setMainStore']);
        Route::put('/{id}/toggle-general-goods', [MerchantsController::class, 'toggleGeneralGoods']);
        Route::post('/{id}/update-chat-id', [MerchantsController::class, 'updateChatId']);
        Route::post('/{id}/upload-logo', [MerchantsController::class, 'uploadLogo']);
        Route::post('/{id}/remove-logo', [MerchantsController::class, 'removeLogo']);
        Route::post('/{id}/attach-competitor', [MerchantsController::class, 'attachCompetitor']);
        Route::match(['put', 'patch'], '/{id}/update-competitor', [MerchantsController::class, 'updateCompetitor']);
        Route::delete('/{id}/detach-competitor', [MerchantsController::class, 'detachCompetitor']);
        Route::post('/{id}/set-responsible-user', [MerchantsController::class, 'setResponsibleUser']);
        Route::match(['put', 'patch'], '/{id}/toggle', [MerchantsController::class, 'toggle']);
        Route::match(['put', 'patch'], '/{id}/toggle-recommend', [MerchantsController::class, 'toggleRecommend']);
        Route::match(['put', 'patch'], '/{id}/toggle-holding-initial-payment', [MerchantsController::class, 'toggleHoldingInitialPayment']);
        Route::post('/{id}/set-tags', [MerchantsController::class, 'setTags']);

        Route::match(['put', 'patch'], '/{id}/update-modules', [MerchantsController::class, 'updateModules']);
    });
