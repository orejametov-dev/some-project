<?php

namespace App\Http\Controllers\ApiCallsGateway\Merchants;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiCallsGateway\Stores\StoreResource;
use App\Models\Merchant;
use App\UseCases\Merchants\FindMerchantByIdUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantsController extends Controller
{
    public function getMerchantStores(Request $request, int $merchant_id, FindMerchantByIdUseCase $findMerchantByIdUseCase): JsonResource
    {
        /** @var Merchant $merchant */
        $merchant = $findMerchantByIdUseCase->execute($merchant_id);
        $store = $merchant->stores()->where('name', $request->query('store_name'))
            ->get();

        return StoreResource::collection($store);
    }
}
