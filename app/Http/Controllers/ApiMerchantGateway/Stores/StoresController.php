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

        return Cache::remember($request->fullUrl() . '_' . $this->merchant_id, 5 * 60, function () use ($stores, $request) {
            return $stores->paginate($request->query('per_page') ?? 15);
        });
    }
}
