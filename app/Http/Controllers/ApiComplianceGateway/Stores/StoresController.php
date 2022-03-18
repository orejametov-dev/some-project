<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiComplianceGateway\Stores;

use App\Filters\Store\QStoreFilter;
use App\Filters\Store\StoreIdsFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiComplianceGateway\Stores\StoresResource;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StoresController extends Controller
{
    public function index(Request $request)
    {
        return Cache::tags('store_index')->remember($request->fullUrl(), 600, function () use ($request) {
            $storesQuery = Store::query()
                ->filterRequest($request, [
                    StoreIdsFilter::class,
                    QStoreFilter::class,
                ]);

            return StoresResource::collection($storesQuery->paginate($request->query('per_page') ?? 15));
        });
    }
}
