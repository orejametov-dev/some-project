<?php

namespace App\Http\Controllers\ApiGateway\Stores;

use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\Stores\StoreStoresRequest;
use App\Http\Requests\ApiPrm\Stores\UpdateStoresRequest;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StoresController extends ApiBaseController
{
    public function index(Request $request)
    {
        $stores = Store::query()->with(['merchant'])->filterRequest($request);

        if ($request->query('object') == 'true') {
            return $stores->first();
        }

        if ($request->has('paginate') && ($request->query('paginate') == 'false'
                OR $request->query('paginate') == 0)) {
            return $stores->get();
        }

        return $stores->paginate($request->query('per_page'));
    }

//    public function index(Request $request)
//    {
//        $stores = Store::query()->with(['merchant'])->filterRequest($request);
//
//        if ($request->query('object') == 'true') {
//            return Cache::remember($request->fullUrl(), 5 * 60, function () use ($stores) {
//                return $stores->first();
//            });
//        }
//
//        if ($request->has('paginate') && ($request->query('paginate') == 'false'
//                OR $request->query('paginate') == 0)) {
//            return Cache::remember($request->fullUrl(), 5 * 60, function () use ($stores) {
//                return $stores->get();
//            });
//        }
//
//        return Cache::remember($request->fullUrl(), 5 * 60, function () use ($stores, $request) {
//            return $stores->paginate($request->query('per_page') ?? 15);
//        });
//    }

    public function show($store_id)
    {
        $store = Store::with('merchant')->findOrFail($store_id);
        return $store;
    }

    public function store(StoreStoresRequest $request)
    {
        $merchant = Merchant::findOrFail($request->merchant_id);

        if ($merchant->stores()->count()) {
            return $store = $merchant->stores()->create($request->all());
        }
        $store = $merchant->stores()->create(array_merge($request->all(), ['is_main' => true]));

        return $store;
    }

    public function update(UpdateStoresRequest $request, $store_id)
    {
        $store = Store::query()->findOrFail($store_id);

        $store->fill($request->all());
        $store->save();

        return $store;
    }

    public function destroy($id)
    {
        $store = Store::findOrFail($id);

        // TODO fix
        DB::transaction(function () use ($store) {
            $store->application_conditions()->delete();
            $store->delete();
        });

        return response()->json(['message' => 'Успешно удалено']);
    }

    public function setStatus($id)
    {
        $store = Store::findOrFail($id);
        $store->is_archived = !$store->is_archived;
        $store->save();

        return $store;
    }

}
