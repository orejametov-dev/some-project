<?php

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\DTOs\MerchantInfos\StoreMerchantInfoDTO;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\Merchants\StoreMerchantInfo;
use App\Http\Requests\ApiPrm\Merchants\UpdateMerchantInfo;
use App\Modules\Merchants\Models\MerchantInfo;
use App\Services\WordService;
use App\UseCases\MerchantInfos\StoreMerchantInfoUseCase;
use Illuminate\Http\Request;

class MerchantInfoController extends Controller
{
    public function index(Request $request)
    {
        $merchantInfoQuery = MerchantInfo::query()->filterRequest($request);

        if ($request->query('object') == true) {
            return $merchantInfoQuery->first();
        }

        return $merchantInfoQuery->paginate($request->query('per_page') ?? 15);
    }

    public function store(StoreMerchantInfo $request, StoreMerchantInfoUseCase $storeMerchantInfoUseCase)
    {
        $merchantInfo = $storeMerchantInfoUseCase->execute(StoreMerchantInfoDTO::fromArray($request->validated()));

        return $merchantInfo;
    }

    public function update(UpdateMerchantInfo $request, $id)
    {
        $merchant_info = MerchantInfo::query()->findOrFail($id);

        $merchant_info->fill($request->validated());
        $merchant_info->save();

        return $merchant_info;
    }

    public function getContractTrust(WordService $wordService, $id)
    {
        $merchant_info = MerchantInfo::query()->findOrFail($id);

        $contract_path = 'app/prm_merchant_contract_trust.docx';
        $contract_file = $wordService->createContract($merchant_info, $contract_path);

        return response()->download(storage_path($contract_file))->deleteFileAfterSend();
    }

    public function getContractProcuration($id, WordService $wordService)
    {
        $merchant_info = MerchantInfo::query()->find($id);

        if ($merchant_info === null) {
            throw new BusinessException('Информация про мерчант не найдена', 'object_not_found', 404);
        }

        $contract_path = 'app/prm_merchant_contract_procuration.docx';
        $contract_file = $wordService->createContract($merchant_info, $contract_path);

        return response()->download(storage_path($contract_file))->deleteFileAfterSend();
    }

    public function getContract(WordService $wordService, $id)
    {
        $merchant_info = MerchantInfo::query()->findOrFail($id);

        $contract_path = 'app/prm_merchant_contract.docx';
        $contract_file = $wordService->createContract($merchant_info, $contract_path);

        return response()->download(storage_path($contract_file))->deleteFileAfterSend();
    }
}
