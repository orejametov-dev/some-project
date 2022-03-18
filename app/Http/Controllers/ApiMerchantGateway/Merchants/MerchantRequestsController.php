<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiMerchantGateway\Merchants;

use App\DTOs\MerchantRequest\StoreMerchantRequestDTO;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiMerchantsGateway\Merchants\MerchantRequestStoreMain;
use App\Models\MerchantRequest;
use App\Services\DistrictService;
use App\Services\LegalNameService;
use App\Services\RegionService;
use App\UseCases\MerchantRequests\StoreMerchantRequestUseCase;
use Illuminate\Http\Request;

class MerchantRequestsController extends Controller
{
    public function app(): array
    {
        $regions = RegionService::getRegions();
        $legal_name_prefixes = LegalNameService::getNamePrefixes();

        return [
            'regions' => $regions,
            'legal_name_prefixes' => $legal_name_prefixes,
        ];
    }

    public function show($id): MerchantRequest
    {
        $merchant_request = MerchantRequest::query()->with('files')->find($id);

        if ($merchant_request === null) {
            throw new BusinessException('Запрос мерчанта не найден', 'merchant_request_not_found', 404);
        }

        return $merchant_request;
    }

    public function storeMain(MerchantRequestStoreMain $request, StoreMerchantRequestUseCase $storeMerchantRequestUseCase): MerchantRequest
    {
        return $storeMerchantRequestUseCase->execute(StoreMerchantRequestDTO::fromArray($request->validated()), false);
    }

    public function getDistricts(Request $request): array
    {
        if ($request->query('region')) {
            return DistrictService::getDistrictsByRegion($request->query('region'));
        }

        return DistrictService::getDistricts();
    }
}
