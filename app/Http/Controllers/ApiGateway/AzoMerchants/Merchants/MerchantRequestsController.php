<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\DTOs\MerchantRequest\StoreMerchantRequestDTO;
use App\Exceptions\BusinessException;
use App\Filters\CommonFilters\StatusIdFilter;
use App\Filters\MerchantRequest\CreatedFromNameFilter;
use App\Filters\MerchantRequest\QMerchantRequestFilter;
use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\MerchantRequests\MerchantRequestStore;
use App\Http\Requests\ApiPrm\MerchantRequests\MerchantRequestStoreDocuments;
use App\Http\Requests\ApiPrm\MerchantRequests\MerchantRequestUpdateRequest;
use App\Http\Requests\ApiPrm\MerchantRequests\MerchantRequestUploadFile;
use App\Http\Resources\ApiPrmGateway\Merchants\MerchantRequestsResource;
use App\HttpRepositories\Auth\AuthHttpRepository;
use App\HttpRepositories\Prm\CompanyHttpRepository;
use App\Models\CancelReason;
use App\Models\MerchantRequest;
use App\UseCases\MerchantRequests\AllowMerchantRequestUseCase;
use App\UseCases\MerchantRequests\StoreMerchantRequestUseCase;
use Illuminate\Http\Request;

class MerchantRequestsController extends ApiBaseController
{
    public function index(Request $request)
    {
        $merchantRequests = MerchantRequest::query()
            ->filterRequest($request, [
                QMerchantRequestFilter::class,
                StatusIdFilter::class,
                CreatedFromNameFilter::class,
            ])
            ->orderRequest($request);

        if ($request->query('object') == true) {
            return $merchantRequests->first();
        }

        if ($request->has('paginate') && $request->query('paginate') == false) {
            return $merchantRequests->get();
        }

        return MerchantRequestsResource::collection($merchantRequests->paginate($request->query('per_page') ?? 15));
    }

    public function show($id)
    {
        $merchant_request = MerchantRequest::query()->with('files')->findOrFail($id);

        return $merchant_request;
    }

    public function store(MerchantRequestStore $request, StoreMerchantRequestUseCase $storeMerchantRequestUseCase)
    {
        return $storeMerchantRequestUseCase->execute(StoreMerchantRequestDTO::fromArray($request->validated()), true);
    }

    public function update($id, MerchantRequestUpdateRequest $request, CompanyHttpRepository $companyHttpRepository)
    {
        $merchant_request = MerchantRequest::query()->find($id);

        if ($merchant_request === null) {
            throw new BusinessException('Запрос не мерчанта не найден', 'merchant_request_not_found', 404);
        }

        if ($companyHttpRepository->getCompanyByName($request->input('name'))) {
            throw new BusinessException('Указанное имя компании уже занято', 'object_not_found', 400);
        }

        $merchant_request->fill($request->validated());

        $merchant_request->save();
        $merchant_request->checkToMainCompleted();

        return $merchant_request;
    }

    public function storeDocuments($id, MerchantRequestStoreDocuments $request)
    {
        $merchant_request = MerchantRequest::query()->find($id);

        if ($merchant_request === null) {
            throw new BusinessException('Запрос на мерчанта не найден', 'merchant_request_not_found', 404);
        }

        $merchant_request->fill($request->validated());

        $merchant_request->save();
        $merchant_request->checkToDocumentsCompleted();

        return $merchant_request;
    }

    public function upload($id, MerchantRequestUploadFile $request)
    {
        $merchant_request = MerchantRequest::query()->find($id);

        if ($merchant_request === null) {
            throw new BusinessException('Запрос на мерчанта не найден', 'merchant_request_not_found', 404);
        }

        $merchant_request_file = $merchant_request->uploadFile($request->file('file'), $request->input('file_type'));
        $merchant_request->checkToFileCompleted();

        return $merchant_request_file;
    }

    public function deleteFile($id, Request $request)
    {
        $this->validate($request, [
            'file_id' => 'required|integer',
        ]);

        $merchant_request = MerchantRequest::query()->find($id);

        if ($merchant_request === null) {
            throw new BusinessException('Запрос не мерчанта не найден', 'merchant_request_not_found', 404);
        }

        $merchant_request->deleteFile($request->input('file_id'));

        return response()->json(['message' => 'Файл успешно удалён.']);
    }

    public function setEngage($id, Request $request, AuthHttpRepository $authHttpRepository)
    {
        $this->validate($request, [
            'engaged_by_id' => 'required|integer',
        ]);

        $user = $authHttpRepository->getUserById((int) $request->input('engaged_by_id'));

        if (!$user) {
            throw new BusinessException('Пользователь не найден', 'user_not_exists', 404);
        }

        $merchant_request = MerchantRequest::query()->findOrFail($id);

        if ($merchant_request->isStatusNew() || $merchant_request->isInProcess()) {
            $merchant_request->engaged_by_id = $user->id;
            $merchant_request->engaged_by_name = $user->name;
            $merchant_request->engaged_at = now();
            $merchant_request->setStatusInProcess();
            $merchant_request->save();

            return $merchant_request;
        }

        return response()->json(['message' => 'Не возможно менять статус']);
    }

    public function allow($id, AllowMerchantRequestUseCase $allowMerchantRequestUseCase)
    {
        $merchant_request = $allowMerchantRequestUseCase->execute((int) $id);

        return $merchant_request;
    }

    public function reject(Request $request, $id)
    {
        $this->validate($request, [
            'cancel_reason_id' => 'required|integer',
        ]);

        $cancelReason = CancelReason::query()->findOrFail($request->input('cancel_reason_id'));
        $merchant_request = MerchantRequest::findOrFail($id);

        if (!$merchant_request->isInProcess()) {
            return response()->json(['message' => 'Статус заявки должен быть "На переговорах"'], 400);
        }

        $merchant_request->setStatusTrash();
        $merchant_request->cancel_reason()->associate($cancelReason);
        $merchant_request->save();

        return $merchant_request;
    }

    public function setOnBoarding($id)
    {
        $merchant_request = MerchantRequest::query()->find($id);

        if ($merchant_request === null) {
            throw new BusinessException('Запрос не найден', 'object_not_found', 404);
        }

        if (($merchant_request->main_completed == true && $merchant_request->documents_completed == true && $merchant_request->file_completed == true) === false) {
            throw new BusinessException('Не все данные были заполнены для одобрения', 'data_not_completed', 400);
        }

        $merchant_request->setStatusOnTraining();
        $merchant_request->save();

        return $merchant_request;
    }
}
