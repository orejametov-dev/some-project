<?php

namespace App\Http\Controllers\ApiMerchantGateway\Merchants;

use App\Http\Controllers\ApiMerchantGateway\ApiBaseController;
use App\Modules\Merchants\Models\Condition;
use App\Modules\Merchants\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApplicationConditionsController extends ApiBaseController
{
    public function index(Request $request)
    {
        return Cache::tags($this->merchant_id)->remember($request->fullUrl() . $this->store_id, 24 * 60, function () use ($request) {
            $store = Store::findOrFail($this->store_id);

            if ($request->has('post_alifshop') and $request->query('post_alifshop') == true) {
                $special_conditions = $store->conditions()
                    ->where('post_alifshop', true)
                    ->active()
                    ->get();

                $conditionQuery = Condition::query()
                    ->active()
                    ->where('post_alifshop', true)
                    ->where('is_special', false)
                    ->byMerchant($this->merchant_id)
                    ->filterRequests($request)
                    ->orderRequest($request);
            } else {
                $special_conditions = $store->conditions()
                    ->where('post_merchant', true)
                    ->active()
                    ->get();

                $conditionQuery = Condition::query()
                    ->active()
                    ->where('post_merchant', true)
                    ->where('is_special', false)
                    ->byMerchant($this->merchant_id)
                    ->filterRequests($request)
                    ->orderRequest($request);
            }

            return array_merge($conditionQuery->get()->toArray(), $special_conditions->toArray());
        });
    }
}
