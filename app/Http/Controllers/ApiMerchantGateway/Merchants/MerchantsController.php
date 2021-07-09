<?php


namespace App\Http\Controllers\ApiMerchantGateway\Merchants;


use App\Http\Controllers\ApiMerchantGateway\ApiBaseController;
use App\Modules\Merchants\Models\Condition;
use App\Modules\Merchants\Models\Merchant;
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

        $merchant = Cache::remember('detail_cache_of_merchant_conditions_' . $this->merchant_id, 60 * 60, function () use ($merchant) {
            return Condition::query()->active()->where('merchant_id', $merchant->id)->get();
        });


        $stores = $merchant->stores;


        return [
            'merchant' => $merchant,
            'conditions' => $conditions,
            'stores' => $stores
        ];
    }
}
