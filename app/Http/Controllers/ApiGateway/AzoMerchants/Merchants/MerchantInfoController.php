<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\DTOs\MerchantInfos\StoreMerchantInfoDTO;
use App\DTOs\MerchantInfos\UpdateMerchantInfoDTO;
use App\Filters\Merchant\MerchantIdFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\Merchants\StoreMerchantInfo;
use App\Http\Requests\ApiPrm\Merchants\UpdateMerchantInfo;
use App\Models\MerchantInfo;
use App\UseCases\MerchantInfos\GetMerchantInfoContractUseCase;
use App\UseCases\MerchantInfos\GetMerchantInfoProcurationContractUseCase;
use App\UseCases\MerchantInfos\GetMerchantInfoTrustContractUseCase;
use App\UseCases\MerchantInfos\StoreMerchantInfoUseCase;
use App\UseCases\MerchantInfos\UpdateMerchantInfoUseCase;
use Illuminate\Http\Request;

class MerchantInfoController extends Controller
{
    public function index(Request $request)
    {
        $merchantInfoQuery = MerchantInfo::query()
            ->with('merchant:id,legal_name,legal_name_prefix')
            ->filterRequest($request, [MerchantIdFilter::class]);

        if ($request->query('object') == true) {
            return $merchantInfoQuery->first();
        }

        return $merchantInfoQuery->paginate($request->query('per_page') ?? 15);
    }

    public function store(StoreMerchantInfo $request, StoreMerchantInfoUseCase $storeMerchantInfoUseCase)
    {
        return $storeMerchantInfoUseCase->execute(StoreMerchantInfoDTO::fromArray($request->validated()));
    }

    public function update(UpdateMerchantInfo $request, $id, UpdateMerchantInfoUseCase $updateMerchantInfoUseCase)
    {
        return $updateMerchantInfoUseCase->execute((int) $id, UpdateMerchantInfoDTO::fromArray($request->validated()));
    }

    public function getContractTrust($id, GetMerchantInfoTrustContractUseCase $getMerchantInfoTrustContractUseCase)
    {
        $file_path = $getMerchantInfoTrustContractUseCase->execute((int) $id);

        return response()->download(storage_path($file_path))->deleteFileAfterSend();
    }

    public function getContractProcuration($id, GetMerchantInfoProcurationContractUseCase $getMerchantInfoProcurationContractUseCase)
    {
        $file_path = $getMerchantInfoProcurationContractUseCase->execute((int) $id);

        return response()->download(storage_path($file_path))->deleteFileAfterSend();
    }

    public function getContract($id, GetMerchantInfoContractUseCase $getMerchantInfoContractUseCase)
    {
        $file_path = $getMerchantInfoContractUseCase->execute((int) $id);

        return response()->download(storage_path($file_path))->deleteFileAfterSend();
    }
}
