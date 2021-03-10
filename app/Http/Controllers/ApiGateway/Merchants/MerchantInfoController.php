<?php

namespace App\Http\Controllers\ApiGateway\Merchants;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\Merchants\StoreMerchantInfo;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\MerchantInfo;
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

    public function store(StoreMerchantInfo $request)
    {
        $this->validate($request, [
            'merchant_id' => 'required|integer'
        ]);

        $merchant = Merchant::query()->findOrFail($request->input('merchant_id'));

        if($merchant->merchant_info) {
            throw new BusinessException('Партнер уже имеет основной договор');
        }

        $merchant_info = new MerchantInfo();

        $merchant_info->legal_name = $request->input('legal_name');
        $merchant_info->director_name = $request->input('director_name');
        $merchant_info->phone = $request->input('phone');
        $merchant_info->vat_number = $request->input('vat_number');
        $merchant_info->mfo = $request->input('mfo');
        $merchant_info->tin = $request->input('tin');
        $merchant_info->oked = $request->input('oked');
        $merchant_info->address = $request->input('address');
        $merchant_info->bank_account = $request->input('bank_account');
        $merchant_info->bank_name = $request->input('bank_name');
        $merchant_info->contract_number = $request->input('contract_number');
        $merchant_info->contract_date = $request->input('contract_date');
        $merchant_info->limit = $request->input('limit');
        $merchant_info->merchant()->associate($merchant);
        $merchant_info->save();

        return $merchant_info;
    }

    public function update(StoreMerchantInfo $request, $id)
    {
        $merchant_info = MerchantInfo::query()->findOrFail($id);

        $merchant_info->fill($request->all());
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
