<?php


namespace App\Http\Controllers\ApiMerchantGateway\Merchants;


use App\Http\Controllers\ApiMerchantGateway\ApiBaseController;
use App\Modules\Merchants\Models\Condition;
use App\Modules\Merchants\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApplicationConditionsController extends ApiBaseController
{
    public function index1(Request $request)
    {
        return Cache::tags($this->merchant_id)->remember($request->fullUrl(), 24 * 60, function () use ($request) {
            if ($request->has('post_alifshop') AND $request->query('post_alifshop') == true) {
                $conditionQuery = Condition::query()
                    ->active()
                    ->where('post_alifshop', true)
                    ->byMerchant($this->merchant_id)
                    ->filterRequest($request)
                    ->orderRequest($request);
            } else {
                $conditionQuery = Condition::query()
                    ->active()
                    ->where('post_merchant', true)
                    ->byMerchant($this->merchant_id)
                    ->filterRequest($request)
                    ->orderRequest($request);
            }

            return $conditionQuery->get();
        });
    }

    public function index(Request $request)
    {
        return Cache::tags($this->merchant_id)->remember($request->fullUrl() . $this->store_id, 2 * 60, function () use ($request) {

            $store = Store::findOrFail($this->store_id);

            $special_conditions = $store->conditions;

            $conditions = Condition::query()
                ->active()
                ->where('is_special', false)
                ->byMerchant($this->merchant_id)
                ->filterRequest($request)
                ->orderRequest($request);

            return array_merge($conditions->get()->toArray(), $special_conditions->toArray());
        });
    }
}
