<?php


namespace App\Http\Controllers\ApiMerchantGateway\Stores;


use App\Http\Controllers\ApiMerchantGateway\ApiBaseController;
use App\Modules\Merchants\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StoresController extends ApiBaseController
{
    public function index(Request $request)
    {
        $stores = Store::query()->with(['merchant'])
            ->byMerchant($this->merchant_id)
            ->filterRequest($request);

        if ($request->query('object') == 'true') {
            return Cache::remember($request->fullUrl() . '_' . $this->merchant_id, 2 * 60, function () use ($stores) {
                return $stores->first();
            });
        }

        if ($request->has('paginate') && ($request->query('paginate') == 'false'
                OR $request->query('paginate') == 0)) {
            return Cache::remember($request->fullUrl() . '_' . $this->merchant_id, 2 * 60, function () use ($stores) {
                return $stores->get();
            });
        }

        return Cache::remember($request->fullUrl() . '_' . $this->merchant_id, 2 * 60, function () use ($stores, $request) {
            return $stores->paginate($request->query('per_page') ?? 15);
        });
    }
}
