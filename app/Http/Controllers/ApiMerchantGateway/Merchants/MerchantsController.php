<?php


namespace App\Http\Controllers\ApiMerchantGateway\Merchants;


use App\Http\Controllers\ApiMerchantGateway\ApiBaseController;
use App\Modules\Merchants\Models\Condition;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\AzoMerchantAccess;
use App\Modules\Merchants\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MerchantsController extends ApiBaseController
{
    //роут для фронт мерчанта
    public function getMerchantDetailsWithRelations(Request $request)
    {
        $merchant = Cache::tags($this->merchant_id)->remember('cache_of_merchant', 60 * 60, function () {
            return Merchant::findOrFail($this->merchant_id);
        });

        $conditions = Cache::tags($this->merchant_id)->remember('cache_of_merchant_conditions', 60 * 60, function () use ($merchant) {
            return Condition::query()
                ->active()
                ->byMerchant($merchant->id)
                ->where('post_merchant', true)
                ->get();
        });

        $stores = Cache::tags($this->merchant_id)->remember('cache_of_merchant_stores', 60 * 60, function () use ($merchant) {
            return Store::query()
                ->azo()
                ->byMerchant($merchant->id)->get();
        });

        $azo_merchant_access = $this->azo_merchant_access;

        $store = Cache::tags($this->merchant_id)->remember($azo_merchant_access->id.'detail_cache_of_merchant_stores', 60 * 60, function () {
            $azo_merchant_access = AzoMerchantAccess::query()->byUserId($this->user->id)->firstOrFail();
            return Store::query()
                ->azo()
                ->findOrFail($azo_merchant_access->store_id);
        });

        return [
            'merchant' => $merchant,
            'conditions' => $conditions,
            'stores' => $stores,
            'store' => $store,
        ];
    }

    public function getMerchantDetailsWithRelations2(Request $request)
    {
        return Cache::tags($this->merchant_id)->remember('merchant_with_details', 60 * 60, function () {
            $merchant = Merchant::findOrFail($this->merchant_id);
            $conditions = Condition::query()->active()->byMerchant($merchant->id)->get();
            $stores = Store::query()->byMerchant($merchant->id)->get();

            $azo_merchant_access = AzoMerchantAccess::query()->byUserId($this->user->id)->firstOrFail();
            $store = Store::query()->findOrFail($azo_merchant_access->store_id);

            return [
                'merchant' => $merchant,
                'conditions' => $conditions,
                'stores' => $stores,
                'store' => $store,
            ];
        });
    }
}
