<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGate\Stores;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiGate\Stores\StoresResource;
use App\Modules\Merchants\Models\Store;

class StoresController extends Controller
{
    public function getStoreByMerchantId($merchant_id, $store_id)
    {
        $store = Store::query()
            ->byMerchant($merchant_id)
            ->findOrFail($store_id);

        return new StoresResource($store);
    }
}
