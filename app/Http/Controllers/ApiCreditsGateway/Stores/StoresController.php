<?php

namespace App\Http\Controllers\ApiCreditsGateway\Stores;

use App\Filters\CommonFilters\StoreIdFilter;
use App\Filters\CommonFilters\StoreIdsFilter;
use App\Filters\Store\GStoreFilter;
use App\Http\Controllers\ApiCreditsGateway\ApiBaseController;
use App\Http\Resources\ApiCredtisGateway\Stores\StoresResource;
use App\Modules\Merchants\Models\Store;
use Illuminate\Http\Request;

class StoresController extends ApiBaseController
{
    public function index(Request $request)
    {
        $stores = Store::query()->with(['merchant'])
            ->filterRequest($request, [
                GStoreFilter::class,
                StoreIdFilter::class,
                StoreIdsFilter::class,
            ]);

        if ($request->query('object') == true) {
            return new StoresResource($stores->first());
        }

        return StoresResource::collection($stores->paginate($request->query('per_page') ?? 15));
    }
}
