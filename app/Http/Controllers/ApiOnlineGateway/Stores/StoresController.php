<?php

namespace App\Http\Controllers\ApiOnlineGateway\Stores;

use App\Filters\CommonFilters\MerchantIdFilter;
use App\Filters\CommonFilters\StoreIdFilter;
use App\Filters\CommonFilters\StoreIdsFilter;
use App\Filters\Store\GStoreFilter;
use App\Filters\Store\RegionFilter;
use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\Store;
use Illuminate\Http\Request;

class StoresController extends Controller
{
    public function index(Request $request)
    {
        $stores = Store::query()
            ->active()
            ->filterRequest($request, [
                GStoreFilter::class,
                StoreIdsFilter::class,
                StoreIdFilter::class,
                RegionFilter::class,
                MerchantIdFilter::class,
            ]);

        if ($request->has('object') and $request->query('object') == true) {
            return $stores->first();
        }

        return $stores->paginate($request->query('per_page') ?? 15);
    }
}
