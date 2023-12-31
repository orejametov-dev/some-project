<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiCreditsGateway\Stores;

use App\Filters\Store\QStoreFilter;
use App\Filters\Store\StoreIdFilter;
use App\Filters\Store\StoreIdsFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiCredtisGateway\Stores\StoresResource;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoresController extends Controller
{
    public function index(Request $request): JsonResource
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
