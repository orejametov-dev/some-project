<?php


namespace App\Http\Controllers\ApiGate\Stores;


use App\Http\Controllers\Controller;
use App\Http\Resources\ApiGate\Stores\StoresResource;
use App\Modules\Merchants\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StoresController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'relations' => 'nullable|array'
        ]);

        $stores = Store::query()->filterRequest($request);

        if ($request->query('relations')){
            $stores->with($request->query('relations'));
        }

        if ($request->query('object') == 'true') {
            return $stores->first();
        }

        if ($request->has('paginate') && $request->query('paginate') == false) {
            return Cache::remember($request->fullUrl(), 600, function () use ($stores) {
                return $stores->get();
            });
        }

        return Cache::remember($request->fullUrl(), 180, function () use ($stores, $request) {
            return $stores->paginate($request->query('per_page'));
        });
    }

    public function show(Request $request, $id)
    {
        $this->validate($request, [
            'relations' => 'nullable|array'
        ]);

        $store = Store::query()->filterRequest($request);

        if ($request->query('relations')){
            $store->with($request->query('relations'));
        }

        return $store->findOrFail($id);
    }

    public function getStoreByMerchantId($merchant_id, $store_id)
    {
        $store = Store::query()->byMerchant($merchant_id)->findOrFail($store_id);

        return new StoresResource($store);
    }
}
