<?php

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Stores;

use App\Http\Controllers\ApiGateway\ApiBaseController;
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
        $stores = Store::query()->with(['merchant'])
            ->azo()
            ->filterRequest($request);

        if ($request->query('object') == 'true') {
            return $stores->first();
        }

        if ($request->has('paginate') && ($request->query('paginate') == 'false'
                or $request->query('paginate') == 0)) {
            return $stores->get();
        }

        return $stores->paginate($request->query('per_page'));
    }

    public function show($store_id)
    {
        $store = Store::with(['merchant', 'activity_reasons'])
            ->azo()
            ->findOrFail($store_id);
        return $store;
    }

    public function store(StoreStoresRequest $request)
    {
        $merchant = Merchant::findOrFail($request->merchant_id);

        $store_exists = Store::query()
            ->where('name', $request->input('name'))
            ->exists();

        if ($store_exists) {
            return response()->json(['message' => 'Указанное имя уже занято другим магазином'], 400);
        }

        $merchant_store = new Store($request->validated());
        $merchant_store->merchant_id = $merchant->id;
        $merchant_store->is_azo = true;

        if (!Store::where('merchant_id', $merchant->id)->count()) {
            $merchant_store->is_main = true;
        }

        if (!$request->input('responsible_person')) {
            $main_store = Store::query()->where('merchant_id', $merchant->id)->main()->first();
            $merchant_store->responsible_person = $main_store->responsible_person;
            $merchant_store->responsible_person_phone = $main_store->responsible_person_phone;
        }

        $merchant_store->save();

        Cache::tags($merchant->id)->flush();
        Cache::tags('azo_merchants')->flush();

        return $merchant_store;
    }

    public function attachAzo($id, Request $request)
    {
        $this->validate($request, [
            'merchant_id' => 'required|integer'
        ]);

        $merchant = Merchant::findOrFail($request->input('merchant_id'));

        $store = $merchant->stores()->find($id); // тут $merchant->store() возвращает все мерчанты с типом azo

        if($store) {
            return response()->json(['Магазин уже сужествует как Аъзо'], 400);
        }

        $store = Store::query()->byMerchant($merchant->id)->findOrFail($id);
        $store->is_azo = true;
        $store->save();

        return $store;
    }

    public function update(UpdateStoresRequest $request, $store_id)
    {
        $store = Store::query()
            ->azo()
            ->findOrFail($store_id);

        $store->fill($request->all());
        $store->save();

        Cache::tags($store->merchant_id)->flush();
        Cache::tags('azo_merchants')->flush();

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
        Cache::tags('azo_merchants')->flush();
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
        Cache::tags('azo_merchants')->flush();

        return $store;
    }

}
