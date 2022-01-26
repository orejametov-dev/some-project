<?php

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\DTOs\MerchantInfos\StoreMerchantInfoDTO;
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

        return $merchantInfoQuery->paginate($request->query('per_page'));
    }

    public function store(StoreMerchantInfo $request, StoreMerchantInfoUseCase $storeMerchantInfoUseCase)
    {
        $merchantInfo = $storeMerchantInfoUseCase->execute(new StoreMerchantInfoDTO(
            merchant_id: (int)$request->input('merchant_id'),
            director_name: (string)$request->input('director_name'),
            legal_name: (string)$request->input('legal_name'),
            legal_name_prefix: (string)$request->input('legal_name_prefix'),
            phone: (string)$request->input('phone'),
            vat_number: (string)$request->input('vat_number'),
            mfo: (string)$request->input('mfo'),
            tin: (string)$request->input('tin'),
            oked: (string)$request->input('oked'),
            bank_account: (string)$request->input('bank_account'),
            bank_name: (string)$request->input('bank_name'),
            address: (string)$request->input('address')
        ));

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

    public function getContract(WordService $wordService, $id)
    {
        $merchant_info = MerchantInfo::query()->findOrFail($id);

        $contract_path = 'app/prm_merchant_contract.docx';
        $contract_file = $wordService->createContract($merchant_info, $contract_path);

        return response()->download(storage_path($contract_file))->deleteFileAfterSend();
    }
}
