<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\DTOs\MerchantInfos\StoreMerchantInfoDTO;
use App\DTOs\MerchantInfos\UpdateMerchantInfoDTO;
use App\Filters\Merchant\MerchantIdFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\Merchants\StoreMerchantInfo;
use App\Http\Requests\ApiPrm\Merchants\UpdateMerchantInfo;
use App\Http\Resources\ApiGateway\MerchantInfo\IndexMerchantInfoResource;
use App\Http\Resources\ApiGateway\MerchantInfo\MerchantInfoResource;
use App\Models\MerchantInfo;
use App\UseCases\MerchantInfos\GetMerchantInfoContractUseCase;
use App\UseCases\MerchantInfos\GetMerchantInfoProcurationContractUseCase;
use App\UseCases\MerchantInfos\GetMerchantInfoTrustContractUseCase;
use App\UseCases\MerchantInfos\StoreMerchantInfoUseCase;
use App\UseCases\MerchantInfos\UpdateMerchantInfoUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MerchantInfoController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $merchantInfoQuery = MerchantInfo::query()
            ->with('merchant:id,legal_name,legal_name_prefix')
            ->filterRequest($request, [MerchantIdFilter::class]);

        if ($request->query('object') == true) {
            return new IndexMerchantInfoResource($merchantInfoQuery->first());
        }

        return IndexMerchantInfoResource::collection($merchantInfoQuery->paginate($request->query('per_page') ?? 15));
    }

    public function store(StoreMerchantInfo $request, StoreMerchantInfoUseCase $storeMerchantInfoUseCase): MerchantInfoResource
    {
        $merchant_info = $storeMerchantInfoUseCase->execute(StoreMerchantInfoDTO::fromArray($request->validated()));

        return new MerchantInfoResource($merchant_info);
    }

    public function update(int $id, UpdateMerchantInfo $request, UpdateMerchantInfoUseCase $updateMerchantInfoUseCase): MerchantInfoResource
    {
        $merchant_info = $updateMerchantInfoUseCase->execute($id, UpdateMerchantInfoDTO::fromArray($request->validated()));

        return new MerchantInfoResource($merchant_info);
    }

    public function getContractTrust(int $id, GetMerchantInfoTrustContractUseCase $getMerchantInfoTrustContractUseCase): BinaryFileResponse
    {
        $file_path = $getMerchantInfoTrustContractUseCase->execute($id);

        return response()->download(storage_path($file_path))->deleteFileAfterSend();
    }

    public function getContractProcuration(int $id, GetMerchantInfoProcurationContractUseCase $getMerchantInfoProcurationContractUseCase): BinaryFileResponse
    {
        $file_path = $getMerchantInfoProcurationContractUseCase->execute(($id));

        return response()->download(storage_path($file_path))->deleteFileAfterSend();
    }

    public function getContract($id, GetMerchantInfoContractUseCase $getMerchantInfoContractUseCase): BinaryFileResponse
    {
        $file_path = $getMerchantInfoContractUseCase->execute((int) $id);

        return response()->download(storage_path($file_path))->deleteFileAfterSend();
    }
}
