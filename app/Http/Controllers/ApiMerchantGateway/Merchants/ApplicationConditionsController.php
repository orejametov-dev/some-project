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
        $conditionQuery = Condition::query()
            ->active()
            ->byMerchant($this->merchant_id)
            ->filterRequest($request)
            ->orderRequest($request);

        if ($request->query('object') == true) {
            Cache::remember($request->fullUrl() . '_' . $this->merchant_id , 5 * 60, function () use ($conditionQuery) {
                return $conditionQuery->first();
            });
        }

        if ($request->has('paginate') && $request->query('paginate') == false) {
            Cache::remember($request->fullUrl() . '_' . $this->merchant_id, 5 * 60 , function () use ($conditionQuery) {
                return $conditionQuery->get();
            });
        }

        return Cache::remember($request->fullUrl() . '_' . $this->merchant_id, 5 * 60, function () use ($conditionQuery, $request) {
            return $conditionQuery->paginate($request->query('per_page') ?? 15);
        });
    }
}
