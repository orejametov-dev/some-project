<?php

use App\Http\Controllers\ApiGateway\Merchants\MerchantsController;
use App\Http\Controllers\ApiGateway\ExtraServices\MerchantsController as ExtraMerchantsController;
use App\Http\Controllers\ApiGateway\Online\MerchantsController as OnlineMerchantsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['service', 'gateway-auth-user'])
    ->group(function () {

        Route::prefix('online')
            ->group(function () {
                Route::prefix('merchants')
                    ->group(function () {
                        Route::put('/', [OnlineMerchantsController::class, 'index']);
                        Route::put('tags', [OnlineMerchantsController::class, 'tags']);
                    });
            });

        Route::prefix('extra-services')->group(function () {
            Route::get('merchants', [ExtraMerchantsController::class, 'index']);
            Route::get('merchants/{merchant_id}/store', [ExtraMerchantsController::class, 'merchantStoreInfo']);
        });

        Route::prefix('merchants/requests')
            ->group(function () {
                Route::get('/', [App\Http\Controllers\ApiGateway\Merchants\MerchantRequestsController::class, 'index']);
                Route::get('/{id}', [App\Http\Controllers\ApiGateway\Merchants\MerchantRequestsController::class, 'show']);
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
                Route::match(['put', 'patch'], '/{id}', [App\Http\Controllers\ApiGateway\Merchants\MerchantUserController::class, 'update']);
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

        Route::prefix('merchants/additional-agreements')
            ->group(function(){
                Route::get('/',[App\Http\Controllers\ApiGateway\Merchants\AdditionalAgreementsController::class,'index']);
                Route::post('/',[App\Http\Controllers\ApiGateway\Merchants\AdditionalAgreementsController::class,'store']);
                Route::put('/{id}',[App\Http\Controllers\ApiGateway\Merchants\AdditionalAgreementsController::class,'update']);
                Route::post('/{id}/documents',[App\Http\Controllers\ApiGateway\Merchants\AdditionalAgreementsController::class,'getAdditionalAgreementDoc']);
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
                Route::match(['put', 'patch'], '/{id}', [App\Http\Controllers\ApiGateway\Stores\StoresController::class, 'index']);
            });

        Route::prefix('app')
            ->group(function () {
                Route::get('/', [App\Http\Controllers\ApiGateway\App\AppController::class, 'index']);
            });

        Route::prefix('application-conditions')
            ->group(function () {
                Route::get('/', [App\Http\Controllers\ApiGateway\Merchants\ApplicationConditionsController::class, 'index']);
                Route::post('/', [App\Http\Controllers\ApiGateway\Merchants\ApplicationConditionsController::class, 'store']);
                Route::match(['put', 'patch'],'/{id}', [App\Http\Controllers\ApiGateway\Merchants\ApplicationConditionsController::class, 'update']);
                Route::post('/{id}/toggle', [App\Http\Controllers\ApiGateway\Merchants\ApplicationConditionsController::class, 'toggle']);
                Route::delete('/{id}', [App\Http\Controllers\ApiGateway\Merchants\ApplicationConditionsController::class, 'delete']);
            });

        Route::prefix('core')
            ->group(function () {
                Route::get('comments', [App\Http\Controllers\ApiGateway\Core\CommentsController::class, 'index']);
                Route::post('comments', [App\Http\Controllers\ApiGateway\Core\CommentsController::class, 'store']);
                Route::match(['put', 'patch'], 'comments/{id}', [App\Http\Controllers\ApiGateway\Core\CommentsController::class, 'update']);
                Route::delete('comments/{id}', [App\Http\Controllers\ApiGateway\Core\CommentsController::class, 'destroy']);

                Route::get('model_hooks', [App\Http\Controllers\ApiGateway\Core\ModelHooksController::class, 'index']);
            });

        Route::prefix('dashboard')
            ->group(function () {
                Route::get('/hot-merchants', [MerchantsController::class, 'hotMerchants']);
                Route::get('/merchant-trends', [App\Http\Controllers\ApiGateway\Dashboard\CreditsController::class, 'index']);
                Route::get('/merchant-trends/{merchant_id}', [App\Http\Controllers\ApiGateway\Dashboard\CreditsController::class, 'show']);
            });

        Route::prefix('tickets')
            ->group(function () {
                Route::get('', [App\Http\Controllers\ApiGateway\Tickets\TicketsController::class, 'index']);
                Route::get('/statuses', [App\Http\Controllers\ApiGateway\Tickets\StatusesController::class, 'index']);
                Route::get('/tags', [App\Http\Controllers\ApiGateway\Tickets\TagsController::class, 'index']);
                Route::get('/new', [App\Http\Controllers\ApiGateway\App\CountersController::class, 'tickets']);
                Route::get('/{id}', [App\Http\Controllers\ApiGateway\Tickets\TicketsController::class, 'show']);

                Route::match(['put', 'patch'], '/{id}/comment', [App\Http\Controllers\ApiGateway\Tickets\TicketsController::class, 'comment']);
                Route::match(['put', 'patch'], '/{id}/finish', [App\Http\Controllers\ApiGateway\Tickets\TicketsController::class, 'finish']);
                Route::match(['put', 'patch'], '/{id}/deadline', [App\Http\Controllers\ApiGateway\Tickets\TicketsController::class, 'deadline']);
                Route::match(['put', 'patch'], '/{id}/assign', [App\Http\Controllers\ApiGateway\Tickets\TicketsController::class, 'assign']);
                Route::match(['put', 'patch'], '/{id}/tags', [App\Http\Controllers\ApiGateway\Tickets\TicketsController::class, 'tags']);
                Route::match(['put', 'patch'], '/{id}/reject', [App\Http\Controllers\ApiGateway\Tickets\TicketsController::class, 'reject']);
                Route::match(['put', 'patch'], '/{id}/status', [App\Http\Controllers\ApiGateway\Tickets\TicketsController::class, 'status']);

            });

        Route::prefix('merchants')
            ->group(function () {
                Route::get('/', [MerchantsController::class, 'index']);
                Route::get('/{id}', [MerchantsController::class, 'show']);
                Route::post('/', [MerchantsController::class, 'store']);
                Route::match(['put', 'patch'],'/{id}', [MerchantsController::class, 'update']);

                Route::post('/{id}/set-main-store', [MerchantsController::class, 'setMainStore']);
                Route::post('/{id}/update-chat-id', [MerchantsController::class, 'updateChatId']);
                Route::post('/{id}/upload-logo', [MerchantsController::class, 'uploadLogo']);
                Route::post('/{id}/remove-logo', [MerchantsController::class, 'removeLogo']);
                Route::post('/{id}/set-responsible-user', [MerchantsController::class, 'setResponsibleUser']);

                Route::match(['put', 'patch'], '/{id}/update-modules', [MerchantsController::class, 'updateModules']);
            });
    });
