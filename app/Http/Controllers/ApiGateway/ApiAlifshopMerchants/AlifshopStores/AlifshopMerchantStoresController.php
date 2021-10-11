<?php

namespace App\Http\Controllers\ApiGateway\ApiAlifshopMerchants\AlifshopStores;

use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\AlifshopMerchant\AlifshopMerchantStoreStoresRequest;
use App\Http\Requests\ApiPrm\AlifshopMerchant\AlishopMerchantUpdateStoreRequest;
use App\Modules\AlifshopMerchants\Models\AlifshopMerchant;
use App\Modules\AlifshopMerchants\Models\AlifshopMerchantStores;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AlifshopMerchantStoresController extends ApiBaseController
{
    public function index(Request $request)
    {
        $alifshop_merchant_stores = AlifshopMerchantStores::query()
            ->with(['alifshop_merchant'])
            ->filterRequest($request);

        return $alifshop_merchant_stores->paginate($request->query('per_page') ?? 15);
    }

    public  function show($store_id)
    {
        $alifshop_merchant_store = AlifshopMerchantStores::query()
            ->with(['alifshop_merchant'])
            ->findOrFail($store_id);
        return $alifshop_merchant_store;
    }

    public function store(AlifshopMerchantStoreStoresRequest $request)
    {
        $alifshop_merchant = AlifshopMerchant::findOrFail($request->input('alifshop_merchant_id'));

        $alifshop_merchant_store = new AlifshopMerchantStores($request->validated());
        $alifshop_merchant_store->alifshop_merchant_id = $alifshop_merchant->id;

        if (!$alifshop_merchant->alifshop_merchant_stores()->count()) {
            $alifshop_merchant_store->is_main = true;
        }

        $alifshop_merchant_store->save();

        Cache::tags($alifshop_merchant->id)->flush();
        Cache::tags('alifshop_merchants')->flush();

        return $alifshop_merchant_store;
    }

    public function update(AlishopMerchantUpdateStoreRequest $request, $store_id)
    {
        $alifshop_merchant_store = AlifshopMerchantStores::query()->findOrFail($store_id);

        $alifshop_merchant_store->fill($request->validated());
        $alifshop_merchant_store->save();

        Cache::tags($alifshop_merchant_store->alifshop_merchant_id)->flush();
        Cache::tags('alifshop_merchants')->flush();

        return $alifshop_merchant_store;
    }

    public function toggle($id, Request $request)
    {
        $this->validate($request, [
            'active' => 'required|boolean'
        ]);

        $alifshop_merchant_store = AlifshopMerchantStores::query()->findOrFail($id);
        $alifshop_merchant_store->active = !$alifshop_merchant_store->active;
        $alifshop_merchant_store->save();

        Cache::tags($alifshop_merchant_store->alifshop_merchant_id)->flush();
        Cache::tags('alifshop_merchants')->flush();

        return $alifshop_merchant_store;
    }
}
