<?php


namespace App\Http\Controllers\ApiMerchantGateway\Merchants;


use App\Http\Controllers\ApiMerchantGateway\ApiBaseController;
use App\Modules\Merchants\Models\Condition;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MerchantsController extends ApiBaseController
{
    //роут для фронт мерчанта
    public function getMerchantDetailsWithRelations(Request $request)
    {
        $merchant = Cache::remember('detail_cache_of_merchant_' . $this->merchant_id, 60 * 60, function () {
            return Merchant::findOrFail($this->merchant_id);
        });

        $conditions = Cache::remember('detail_cache_of_merchant_conditions_' . $this->merchant_id, 60 * 60, function () use ($merchant) {
            return Condition::query()->active()->byMerchant($merchant->id)->get();
        });

        $stores = Cache::remember('detail_cache_of_merchant_stores_' . $this->merchant_id, 60 * 60, function () use ($merchant) {
            $stores = Store::query()->byMerchant($merchant->id)->get();

            foreach ($stores as $store) {
                if($store->id == $this->store_id) {
                    $store->is_merchant_user_store = true;
                    $stores->merge($stores);
                }
            }

            return $stores;
        });

        return [
            'merchant' => $merchant,
            'conditions' => $conditions,
            'stores' => $stores
        ];
    }
}
