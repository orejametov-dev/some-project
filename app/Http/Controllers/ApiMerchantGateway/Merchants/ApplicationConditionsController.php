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
        return Cache::tags($this->merchant_id)->remember($request->fullUrl(), 2 * 60, function () use ($request) {
            $conditionQuery = Condition::query()
                ->active()
                ->where('post_merchant', true)
                ->byMerchant($this->merchant_id)
                ->filterRequest($request)
                ->orderRequest($request);

            if ($request->query('object') == true) {
                return $conditionQuery->first();
            }

            return $conditionQuery->paginate($request->query('per_page') ?? 15);
        });
    }
}
