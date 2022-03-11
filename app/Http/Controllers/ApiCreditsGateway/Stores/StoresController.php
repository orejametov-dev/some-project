<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiCreditsGateway\Stores;

use App\Filters\Store\QStoreFilter;
use App\Filters\Store\StoreIdFilter;
use App\Filters\Store\StoreIdsFilter;
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
                QStoreFilter::class,
                StoreIdFilter::class,
                StoreIdsFilter::class,
            ]);

        if ($request->query('object') == true) {
            return new StoresResource($stores->first());
        }

        return StoresResource::collection($stores->paginate($request->query('per_page') ?? 15));
    }
}
