<?php

namespace App\Http\Controllers\ApiGateway\ApiAlifshopMerchants\AlifshopStores;

use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\AlifshopMerchant\AlifshopMerchantCreateStoresRequest;
use App\Http\Requests\ApiPrm\AlifshopMerchant\AlishopMerchantUpdateStoreRequest;
use App\Modules\AlifshopMerchants\Models\AlifshopMerchant;
use App\Modules\Merchants\Models\ActivityReason;
use App\Modules\Merchants\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AlifshopMerchantStoresController extends ApiBaseController
{
    public function index(Request $request)
    {
        $alifshop_merchant_stores = Store::query()
            ->with(['alifshop_merchant'])
            ->filterRequest($request)
            ->alifshop();

        return $alifshop_merchant_stores->paginate($request->query('per_page') ?? 15);
    }

    public function show($store_id)
    {
        $alifshop_merchant_store = Store::query()
            ->with(['alifshop_merchant'])
            ->alifshop()
            ->findOrFail($store_id);

        return $alifshop_merchant_store;
    }

    public function store(AlifshopMerchantCreateStoresRequest $request)
    {
        $alifshop_merchant = AlifshopMerchant::findOrFail($request->input('alifshop_merchant_id'));

        $alifshop_store_exists = Store::query()
            ->where('name', $request->input('name'))
            ->where('merchant_id', '!=', $alifshop_merchant->id)
            ->exists();

        if ($alifshop_store_exists) {
            return response()->json(['message' => 'Указанное имя уже занято другим магазином'], 400);
        }

        $alifshop_store_exists = $alifshop_merchant->stores()
            ->where('name', $request->input('name'))
            ->exists();

        if ($alifshop_store_exists) {
            return response()->json(['message' => 'Магазин уже является партнером алифшоп'], 400);
        }

        $alifshop_merchant_store = Store::query()
            ->where('name', $request->input('name'))
            ->where('active', true)
            ->where('is_alifshop', false)
            ->where('merchant_id', $alifshop_merchant->id)
            ->firstOrNew();

        $alifshop_merchant_store->name = optional($alifshop_merchant_store)->name ?? $request->input('name');
        $alifshop_merchant_store->region = optional($alifshop_merchant_store)->region ?? $request->input('region');
        $alifshop_merchant_store->merchant_id = $alifshop_merchant->id;
        $alifshop_merchant_store->is_alifshop = true;
        if (!Store::query()->where('merchant_id', $alifshop_merchant->id)->count()) {
            $alifshop_merchant_store->is_main = true;
        }

        $alifshop_merchant_store->save();

        Cache::tags($alifshop_merchant->id)->flush();
        Cache::tags('alifshop_merchants')->flush();

        return $alifshop_merchant_store;
    }

    public function update(AlishopMerchantUpdateStoreRequest $request, $store_id)
    {
        $alifshop_merchant_store = Store::query()->findOrFail($store_id);

        $alifshop_merchant_store->fill($request->validated());
        $alifshop_merchant_store->save();

        Cache::tags($alifshop_merchant_store->merchant_id)->flush();
        Cache::tags('alifshop_merchants')->flush();

        return $alifshop_merchant_store;
    }

    public function toggle($id, Request $request)
    {
        $this->validate($request, [
            'activity_reason_id' => 'integer|required'
        ]);

        $active_reason = ActivityReason::where('type', 'STORE')
            ->findOrFail($request->input('activity_reason_id'));

        $alifshop_merchant_store = Store::query()->findOrFail($id);
        $alifshop_merchant_store->active = !$alifshop_merchant_store->active;
        $alifshop_merchant_store->save();

        $alifshop_merchant_store->activity_reasons()->attach($active_reason, [
            'active' => $alifshop_merchant_store->active,
            'created_by_id' => $this->user->id,
            'created_by_name' => $this->user->name,
        ]);

        Cache::tags($alifshop_merchant_store->merchant_id)->flush();
        Cache::tags('alifshop_merchants')->flush();

        return $alifshop_merchant_store;
    }
}
