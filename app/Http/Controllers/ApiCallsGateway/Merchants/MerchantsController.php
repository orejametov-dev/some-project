<?php


namespace App\Http\Controllers\ApiCallsGateway\Merchants;


use App\Http\Controllers\Controller;
use App\Http\Resources\ApiCallsGateway\Stores\StoreResource;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantsController extends Controller
{
    public function getMerchantStores(Request $request, $merchant_id): JsonResource
    {
        /** @var Merchant $merchant */
        $merchant = Merchant::query()->findOrFail($merchant_id);
        $store = $merchant->stores()->where('name', $request->query('store_name'))
            ->get();

        return StoreResource::collection($store);
    }
}
