<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\DTOs\MerchantRequest\StoreMerchantRequestDocumentsDTO;
use App\DTOs\MerchantRequest\StoreMerchantRequestDTO;
use App\DTOs\MerchantRequest\UpdateMerchantRequestDTO;
use App\Filters\CommonFilters\StatusIdFilter;
use App\Filters\MerchantRequest\CreatedFromNameFilter;
use App\Filters\MerchantRequest\QMerchantRequestFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\MerchantRequests\MerchantRequestStoreDocumentsRequest;
use App\Http\Requests\ApiPrm\MerchantRequests\MerchantRequestStoreRequest;
use App\Http\Requests\ApiPrm\MerchantRequests\MerchantRequestUpdateRequest;
use App\Http\Requests\ApiPrm\MerchantRequests\MerchantRequestUploadFileRequest;
use App\Http\Resources\ApiGateway\Files\FileResource;
use App\Http\Resources\ApiGateway\MerchantRequest\IndexMerchantRequestResource;
use App\Http\Resources\ApiGateway\MerchantRequest\MerchantRequestResource;
use App\Http\Resources\ApiGateway\MerchantRequest\ShowMerchantRequestResource;
use App\Models\MerchantRequest;
use App\UseCases\MerchantRequests\AllowMerchantRequestUseCase;
use App\UseCases\MerchantRequests\DeleteMerchantRequestFileUseCase;
use App\UseCases\MerchantRequests\FindMerchantRequestByIdUseCase;
use App\UseCases\MerchantRequests\RejectMerchantRequestUseCase;
use App\UseCases\MerchantRequests\SetMerchantRequestEngagedUseCase;
use App\UseCases\MerchantRequests\SetMerchantRequestOnBoardingUseCase;
use App\UseCases\MerchantRequests\StoreMerchantRequestDocumentsUseCase;
use App\UseCases\MerchantRequests\StoreMerchantRequestUseCase;
use App\UseCases\MerchantRequests\UpdateMerchantRequestUseCase;
use App\UseCases\MerchantRequests\UploadMerchantRequestFileUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantRequestsController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $merchantRequests = MerchantRequest::query()
            ->filterRequest($request, [
                QMerchantRequestFilter::class,
                StatusIdFilter::class,
                CreatedFromNameFilter::class,
            ])
            ->orderRequest($request);

        if ($request->query('object') == true) {
            return new IndexMerchantRequestResource($merchantRequests->first());
        }

        if ($request->has('paginate') && $request->query('paginate') == false) {
            return IndexMerchantRequestResource::collection($merchantRequests->get());
        }

        return IndexMerchantRequestResource::collection($merchantRequests->paginate($request->query('per_page') ?? 15));
    }

    public function show(int $id, FindMerchantRequestByIdUseCase $findMerchantRequestByIdUseCase): ShowMerchantRequestResource
    {
        $merchant_request = $findMerchantRequestByIdUseCase->execute($id);
        $merchant_request->load('files');

        return new ShowMerchantRequestResource($merchant_request);
    }

    public function store(MerchantRequestStoreRequest $request, StoreMerchantRequestUseCase $storeMerchantRequestUseCase): MerchantRequestResource
    {
        $merchant_request = $storeMerchantRequestUseCase->execute(StoreMerchantRequestDTO::fromArray($request->validated()), true);

        return new MerchantRequestResource($merchant_request);
    }

    public function update(int $id, MerchantRequestUpdateRequest $request, UpdateMerchantRequestUseCase $updateMerchantRequestUseCase): MerchantRequestResource
    {
        $merchant_request = $updateMerchantRequestUseCase->execute($id, UpdateMerchantRequestDTO::fromArray($request->validated()));

        return new MerchantRequestResource($merchant_request);
    }

    public function storeDocuments(int $id, MerchantRequestStoreDocumentsRequest $request, StoreMerchantRequestDocumentsUseCase $merchantRequestDocumentsUseCase): MerchantRequestResource
    {
        $merchant_request = $merchantRequestDocumentsUseCase->execute($id, StoreMerchantRequestDocumentsDTO::fromArray($request->validated()));

        return new MerchantRequestResource($merchant_request);
    }

    public function upload(int $id, MerchantRequestUploadFileRequest $request, UploadMerchantRequestFileUseCase $uploadMerchantRequestFileUseCase): FileResource
    {
        $merchant_request_file = $uploadMerchantRequestFileUseCase->execute($id, $request->input('file_type'), $request->file('file'));

        return new FileResource($merchant_request_file);
    }

    public function deleteFile(int $id, Request $request, DeleteMerchantRequestFileUseCase $deleteMerchantRequestFileUseCase): JsonResponse
    {
        $this->validate($request, [
            'file_id' => 'required|integer',
        ]);

        $deleteMerchantRequestFileUseCase->execute($id, (int) $request->input('file_id'));

        return new JsonResponse(['message' => 'Файл успешно удалён.']);
    }

    public function setEngage(int $id, Request $request, SetMerchantRequestEngagedUseCase $setMerchantRequestEngagedUseCase): MerchantRequestResource
    {
        $this->validate($request, [
            'engaged_by_id' => 'required|integer',
        ]);

        $merchant_request = $setMerchantRequestEngagedUseCase->execute($id, (int) $request->input('engaged_by_id'));

        return new MerchantRequestResource($merchant_request);
    }

    public function allow(int $id, AllowMerchantRequestUseCase $allowMerchantRequestUseCase): MerchantRequestResource
    {
        $merchant_request = $allowMerchantRequestUseCase->execute($id);

        return new MerchantRequestResource($merchant_request);
    }

    public function reject(int $id, Request $request, RejectMerchantRequestUseCase $rejectMerchantRequestUseCase): MerchantRequestResource
    {
        $this->validate($request, [
            'cancel_reason_id' => 'required|integer',
        ]);

        $merchant_request = $rejectMerchantRequestUseCase->execute($id, (int) $request->input('cancel_reason_id'));

        return new MerchantRequestResource($merchant_request);
    }

    public function setOnBoarding(int $id, SetMerchantRequestOnBoardingUseCase $setMerchantRequestOnBoardingUseCase): MerchantRequestResource
    {
        $merchant_request = $setMerchantRequestOnBoardingUseCase->execute($id);

        return new MerchantRequestResource($merchant_request);
    }
}
