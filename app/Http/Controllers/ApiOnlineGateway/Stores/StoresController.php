<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiOnlineGateway\Stores;

use App\Filters\Merchant\MerchantIdFilter;
use App\Filters\Store\QStoreFilter;
use App\Filters\Store\RegionFilter;
use App\Filters\Store\StoreIdFilter;
use App\Filters\Store\StoreIdsFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiOnlineGateway\StoresResource;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StoresController extends Controller
{
    public function index(Request $request): ResourceCollection|StoresResource
    {
        $stores = Store::query()
            ->active()
            ->filterRequest($request, [
                QStoreFilter::class,
                StoreIdsFilter::class,
                StoreIdFilter::class,
                RegionFilter::class,
                MerchantIdFilter::class,
            ]);

        if ($request->has('object') and $request->query('object') == true) {
            return new StoresResource($stores->first());
        }

        return StoresResource::collection($stores->paginate($request->query('per_page') ?? 15));
    }
}
