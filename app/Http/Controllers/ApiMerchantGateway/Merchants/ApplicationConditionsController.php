<?php


namespace App\Http\Controllers\ApiMerchantGateway\Merchants;


use App\Http\Controllers\ApiMerchantGateway\ApiBaseController;
use App\Modules\Merchants\Models\Condition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApplicationConditionsController extends ApiBaseController
{
    public function index(Request $request)
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
}
