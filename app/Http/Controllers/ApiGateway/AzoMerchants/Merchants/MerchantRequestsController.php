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
use App\Http\Resources\ApiPrmGateway\Merchants\MerchantRequestsResource;
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
use Illuminate\Http\Request;

class MerchantRequestsController extends Controller
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

    public function show($id, FindMerchantRequestByIdUseCase $findMerchantRequestByIdUseCase)
    {
        return $findMerchantRequestByIdUseCase->execute((int) $id);
    }

    public function store(MerchantRequestStoreRequest $request, StoreMerchantRequestUseCase $storeMerchantRequestUseCase)
    {
        return $storeMerchantRequestUseCase->execute(StoreMerchantRequestDTO::fromArray($request->validated()), true);
    }

    public function update($id, MerchantRequestUpdateRequest $request, UpdateMerchantRequestUseCase $updateMerchantRequestUseCase)
    {
        return $updateMerchantRequestUseCase->execute((int) $id, UpdateMerchantRequestDTO::fromArray($request->validated()));
    }

    public function storeDocuments($id, MerchantRequestStoreDocumentsRequest $request, StoreMerchantRequestDocumentsUseCase $merchantRequestDocumentsUseCase)
    {
        return $merchantRequestDocumentsUseCase->execute((int) $id, StoreMerchantRequestDocumentsDTO::fromArray($request->validated()));
    }

    public function upload($id, MerchantRequestUploadFileRequest $request, UploadMerchantRequestFileUseCase $uploadMerchantRequestFileUseCase)
    {
        return $uploadMerchantRequestFileUseCase->execute((int) $id, $request->input('file_type'), $request->file('file'));
    }

    public function deleteFile($id, Request $request, DeleteMerchantRequestFileUseCase $deleteMerchantRequestFileUseCase)
    {
        $this->validate($request, [
            'file_id' => 'required|integer',
        ]);

        $deleteMerchantRequestFileUseCase->execute((int) $id, (int) $request->input('file_id'));

        return response()->json(['message' => 'Файл успешно удалён.']);
    }

    public function setEngage($id, Request $request, SetMerchantRequestEngagedUseCase $setMerchantRequestEngagedUseCase)
    {
        $this->validate($request, [
            'engaged_by_id' => 'required|integer',
        ]);

        return $setMerchantRequestEngagedUseCase->execute((int) $id, (int) $request->input('engaged_by_id'));
    }

    public function allow($id, AllowMerchantRequestUseCase $allowMerchantRequestUseCase)
    {
        return $allowMerchantRequestUseCase->execute((int) $id);
    }

    public function reject(Request $request, $id, RejectMerchantRequestUseCase $rejectMerchantRequestUseCase)
    {
        $this->validate($request, [
            'cancel_reason_id' => 'required|integer',
        ]);

        return $rejectMerchantRequestUseCase->execute((int) $id, (int) $request->input('cancel_reason_id'));
    }

    public function setOnBoarding($id, SetMerchantRequestOnBoardingUseCase $setMerchantRequestOnBoardingUseCase)
    {
        return $setMerchantRequestOnBoardingUseCase->execute((int) $id);
    }
}
