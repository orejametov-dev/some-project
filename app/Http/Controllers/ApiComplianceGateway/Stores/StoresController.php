<?php


namespace App\Http\Controllers\ApiComplianceGateway\Stores;


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
                ->filterRequest($request);

            return StoresResource::collection($storesQuery->paginate($request->query('per_page') ?? 15));
        });
    }

    public function index2(Request $request)
    {
        $stores = Store::query()->filterRequest($request);

        if ($request->has('paginate') && $request->query('paginate') == false) {
            return Cache::remember($request->fullUrl(), 600, function () use ($stores) {
                return StoresResource::collection($stores->get());
            });
        }

        return Cache::remember($request->fullUrl(), 180, function () use ($stores, $request) {
            return StoresResource::collection($stores->paginate($request->query('per_page')) ?? 15);
        });
    }
}
