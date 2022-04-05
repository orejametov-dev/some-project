<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiMerchantGateway\Merchants;

use App\DTOs\MerchantRequest\StoreMerchantRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiMerchantsGateway\Merchants\MerchantRequestStoreMain;
use App\Http\Resources\ApiMerchantGateway\MerchantRequest\MerchantRequestResource;
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

    public function storeMain(MerchantRequestStoreMain $request, StoreMerchantRequestUseCase $storeMerchantRequestUseCase): MerchantRequestResource
    {
        $merchant_request = $storeMerchantRequestUseCase->execute(StoreMerchantRequestDTO::fromArray($request->validated()), false);

        return new MerchantRequestResource($merchant_request);
    }

    public function getDistricts(Request $request): array
    {
        if ($request->query('region')) {
            return DistrictService::getDistrictsByRegion($request->query('region'));
        }

        return DistrictService::getDistricts();
    }
}
