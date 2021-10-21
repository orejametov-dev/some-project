<?php

namespace App\Http\Controllers\ApiGateway\Stores;

use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\Stores\StoreStoresRequest;
use App\Http\Requests\ApiPrm\Stores\UpdateStoresRequest;
use App\Modules\Merchants\Models\ActivityReason;
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

    public function show($store_id)
    {
        $store = Store::with(['merchant', 'activity_reasons'])
            ->findOrFail($store_id);
        return $store;
    }

    public function store(StoreStoresRequest $request)
    {
        $merchant = Merchant::findOrFail($request->merchant_id);

        if ($merchant->stores()->count()) {
            $store = new Store($request->validated());
            if (!$request->input('responsible_person')) {
                $main_store = $merchant->stores()->main()->first();
                $store->responsible_person = $main_store->responsible_person;
                $store->responsible_person_phone = $main_store->responsible_person_phone;
            }
            $store->merchant_id = $merchant->id;
            $store->save();

            return $store;
        }
        $store = $merchant->stores()->create(array_merge($request->all(), ['is_main' => true]));

        Cache::tags($merchant->id)->flush();
        Cache::tags('merchants')->flush();

        return $store;
    }

    public function update(UpdateStoresRequest $request, $store_id)
    {
        $store = Store::query()->findOrFail($store_id);

        $store->fill($request->all());
        $store->save();

        Cache::tags($store->merchant_id)->flush();
        Cache::tags('merchants')->flush();

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

        Cache::tags($store->merchant_id)->flush();
        Cache::tags('merchants')->flush();
        return response()->json(['message' => 'Успешно удалено']);
    }

    public function toggle(Request $request, $id)
    {
        $this->validate($request, [
            'activity_reason_id' => 'integer|required'
        ]);

        $active_reason = ActivityReason::where('type', 'STORE')
            ->findOrFail($request->input('activity_reason_id'));

        $store = Store::findOrFail($id);
        $store->active = !$store->active;
        $store->save();

        $store->activity_reasons()->attach($active_reason, [
            'active' => $store->active,
            'created_by_id' => $this->user->id,
            'created_by_name' => $this->user->name
        ]);

        Cache::tags($store->merchant_id)->flush();
        Cache::tags('merchants')->flush();

        return $store;
    }

}
