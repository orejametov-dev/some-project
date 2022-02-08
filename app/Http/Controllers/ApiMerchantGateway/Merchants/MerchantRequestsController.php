<?php

namespace App\Http\Controllers\ApiMerchantGateway\Merchants;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiMerchantsGateway\Merchants\MerchantRequestStoreMain;
use App\HttpServices\Company\CompanyService;
use App\Modules\Merchants\Models\Request as MerchantRequest;
use App\Services\DistrictService;
use App\Services\LegalNameService;
use App\Services\RegionService;
use Illuminate\Http\Request;

class MerchantRequestsController extends Controller
{
    public function app()
    {
        $regions = RegionService::getRegions();
        $legal_name_prefixes = LegalNameService::getNamePrefixes();

        return [
            'regions' => $regions,
            'legal_name_prefixes' => $legal_name_prefixes,
        ];
    }

    public function show($id)
    {
        $merchant_request = MerchantRequest::with('files')->find($id);

        if ($merchant_request === null) {
            throw new BusinessException('Запрос мерчанта не найден', 'merchant_request_not_found', 404);
        }

        return $merchant_request;
    }

    public function storeMain(MerchantRequestStoreMain $request)
    {
        $merchant_request = MerchantRequest::query()
            ->where('user_phone', $request->user_phone)
            ->first();

        if ($merchant_request) {
            throw new BusinessException('Запрос с таким номером телефона уже существует, статус запроса '
                . MerchantRequest::getOneById((int) $merchant_request->status_id)->name);
        }

        if (CompanyService::getCompanyByName($request->input('name'))) {
            return response()->json(['message' => 'Указанное имя компании уже занято'], 400);
        }

        $merchant_request = new MerchantRequest();
        $merchant_request->fill($request->validated());
        $merchant_request->setStatusNew();

        $merchant_request->save();
        $merchant_request->checkToMainCompleted();

        return $merchant_request;
    }

    public function getDistricts(Request $request)
    {
        if ($request->query('region')) {
            return DistrictService::getDistrictsByRegion($request->query('region'));
        }

        return DistrictService::getDistricts();
    }
}
