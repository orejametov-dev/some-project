<?php

namespace App\Http\Controllers\ApiGateway\Merchants;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\Merchants\StoreMerchantInfo;
use App\Modules\Merchants\DTO\Merchants\MerchantInfoDTO;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\MerchantInfo;
use App\Modules\Merchants\Services\Merchants\MerchantsService;
use App\Services\WordService;
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

    public function store(StoreMerchantInfo $request, MerchantsService $merchantsService)
    {
        $this->validate($request, [
            'merchant_id' => 'required|integer'
        ]);

        $merchant = Merchant::query()->findOrFail($request->input('merchant_id'));

        if($merchant->merchant_info) {
            throw new BusinessException('Партнер уже имеет основной договор');
        }

        $merchant_info = $merchantsService->createMerchantInfo((new MerchantInfoDTO())->fromHttpRequest($request), $merchant);

        return $merchant_info;
    }

    public function update(StoreMerchantInfo $request, $id)
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
