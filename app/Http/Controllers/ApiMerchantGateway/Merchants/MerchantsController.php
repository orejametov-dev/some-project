<?php


namespace App\Http\Controllers\ApiMerchantGateway\Merchants;


use App\Http\Controllers\ApiMerchantGateway\ApiBaseController;
use App\Modules\Merchants\Models\Condition;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\MerchantUser;
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
            return Condition::query()->active()->byMerchant($merchant->id)->get();
        });

        $stores = Cache::tags($this->merchant_id)->remember('cache_of_merchant_stores', 60 * 60, function () use ($merchant) {
            return Store::query()->byMerchant($merchant->id)->get();
        });

        $merchant_user = $this->user->merchant_user;

        $store = Cache::tags($this->merchant_id)->remember($merchant_user->id.'detail_cache_of_merchant_stores', 60 * 60, function () {
            $merchant_user = MerchantUser::query()->byUserId($this->user->id)->firstOrFail();
            return Store::query()->findOrFail($merchant_user->store_id);
        });

        return [
            'merchant' => $merchant,
            'conditions' => $conditions,
            'stores' => $stores,
            'store' => $store,
        ];
    }

//    public function getMerchantDetailsWithRelations(Request $request)
//    {
//        return Cache::tags($this->merchant_id)->remember($request->fullUrl(), 60 * 60, function () {
//            $merchant = Merchant::findOrFail($this->merchant_id);
//            $conditions = Condition::query()->active()->byMerchant($merchant->id)->get();
//            $stores = Store::query()->byMerchant($merchant->id)->get();
//
//            $merchant_user = MerchantUser::query()->byUserId($this->user->id)->firstOrFail();
//            $store = Store::query()->findOrFail($merchant_user->store_id);
//
//            return [
//                'merchant' => $merchant,
//                'conditions' => $conditions,
//                'stores' => $stores,
//                'store' => $store,
//            ];
//        });
//    }
}
