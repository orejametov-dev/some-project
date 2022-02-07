<?php

namespace App\Http\Controllers\ApiGateway\AzoMerchants\ExtraServices;

use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\Merchant;
use Illuminate\Http\Request;

class MerchantsController extends Controller
{
    public function index(Request $request)
    {
        $query = Merchant::query()
            ->filterRequest($request)
            ->latest();

        if ($request->query('object') == true) {
            return $query->first();
        }

        return $query->paginate($request->query('per_page') ?? 15);
    }

    public function merchantStoreInfo(Request $request, $merchant_id)
    {
        /** @var Merchant $merchant */
        $merchant = Merchant::query()->findOrFail($merchant_id);
        $store = $merchant->stores()->where('name', $request->query('store_name'))
            ->get(['id', 'merchant_id', 'name', 'address', 'phone', 'responsible_person']);

        return response()->json($store);
    }
}
