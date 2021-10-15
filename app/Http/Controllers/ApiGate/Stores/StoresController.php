<?php


namespace App\Http\Controllers\ApiGate\Stores;


use App\Http\Controllers\Controller;
use App\Http\Resources\ApiGate\Stores\StoresResource;
use App\Modules\Merchants\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StoresController extends Controller
{
    public function getStoreByMerchantId($merchant_id, $store_id)
    {
        $store = Store::query()
            ->azo()
            ->byMerchant($merchant_id)
            ->findOrFail($store_id);

        return new StoresResource($store);
    }
}
