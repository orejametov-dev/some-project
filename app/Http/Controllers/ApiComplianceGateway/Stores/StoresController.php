<?php

namespace App\Http\Controllers\ApiComplianceGateway\Stores;

use App\Filters\Store\GStoreFilter;
use App\Filters\Store\StoreIdsFilter;
use App\Http\Controllers\ApiComplianceGateway\ApiBaseController;
use App\Http\Resources\ApiComplianceGateway\Stores\StoresResource;
use App\Modules\Merchants\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StoresController extends ApiBaseController
{
    public function index(Request $request)
    {
        return Cache::tags('store_index')->remember($request->fullUrl(), 600, function () use ($request) {
            $storesQuery = Store::query()
                ->filterRequest($request, [
                    StoreIdsFilter::class,
                    GStoreFilter::class,
                ]);

            return StoresResource::collection($storesQuery->paginate($request->query('per_page') ?? 15));
        });
    }
}
