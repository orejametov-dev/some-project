<?php

namespace App\Http\Controllers\ApiGateway\ApiAlifshopMerchants\AlifshopStores;

use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\AlifshopMerchant\AlifshopMerchantCreateStoresRequest;
use App\Http\Requests\ApiPrm\AlifshopMerchant\AlishopMerchantUpdateStoreRequest;
use App\Modules\AlifshopMerchants\Models\AlifshopMerchant;
use App\Modules\AlifshopMerchants\Models\AlifshopMerchantStore;
use App\Modules\Merchants\Models\ActivityReason;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AlifshopMerchantStoresController extends ApiBaseController
{
    public function index(Request $request)
    {
        $alifshop_merchant_stores = AlifshopMerchantStore::query()
            ->with(['alifshop_merchant'])
            ->filterRequest($request);

        return $alifshop_merchant_stores->paginate($request->query('per_page') ?? 15);
    }

    public function show($store_id)
    {
        $alifshop_merchant_store = AlifshopMerchantStore::query()
            ->with(['alifshop_merchant'])
            ->findOrFail($store_id);
        return $alifshop_merchant_store;
    }

    public function store(AlifshopMerchantCreateStoresRequest $request)
    {
        $alifshop_merchant = AlifshopMerchant::findOrFail($request->input('alifshop_merchant_id'));

        $alifshop_merchant_store = new AlifshopMerchantStore($request->validated());
        $alifshop_merchant_store->alifshop_merchant_id = $alifshop_merchant->id;

        if (!$alifshop_merchant->alifshop_merchant_stores()->count()) {
            $alifshop_merchant_store->is_main = true;
        }

        $alifshop_merchant_store->save();

        //кастыль
        if (!Store::query()->where('merchant_id', $alifshop_merchant->company->id)->exists()) { //не правильно

            $merchant = Merchant::query()->where('company_id', $alifshop_merchant->company->id)->first();
            $store = new Store($request->validated());
            $store->merchant_id = $merchant->id;
            if ($alifshop_merchant_store->is_main)
            {
                $store->is_main = true;
            }
            $store->active = false;

            $store->save();
        }

        Cache::tags($alifshop_merchant->id)->flush();
        Cache::tags('alifshop_merchants')->flush();

        return $alifshop_merchant_store;
    }

    public function update(AlishopMerchantUpdateStoreRequest $request, $store_id)
    {
        $alifshop_merchant_store = AlifshopMerchantStore::query()->findOrFail($store_id);

        $alifshop_merchant_store->fill($request->validated());
        $alifshop_merchant_store->save();

        Cache::tags($alifshop_merchant_store->alifshop_merchant_id)->flush();
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

        $alifshop_merchant_store = AlifshopMerchantStore::query()->findOrFail($id);
        $alifshop_merchant_store->active = !$alifshop_merchant_store->active;
        $alifshop_merchant_store->save();

        $alifshop_merchant_store->activity_reasons()->attach($active_reason, [
            'active' => $alifshop_merchant_store->active,
            'created_by_id' => $this->user->id,
            'created_by_name' => $this->user->name,
        ]);

        Cache::tags($alifshop_merchant_store->alifshop_merchant_id)->flush();
        Cache::tags('alifshop_merchants')->flush();

        return $alifshop_merchant_store;
    }
}
