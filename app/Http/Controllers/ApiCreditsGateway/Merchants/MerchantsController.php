<?php

namespace App\Http\Controllers\ApiCreditsGateway\Merchants;

use App\Http\Controllers\ApiCreditsGateway\ApiBaseController;
use App\Http\Resources\ApiCredtisGateway\Merchants\MerchantsResource;
use App\Http\Resources\ApiCredtisGateway\Merchants\SpecialMerchantResource;
use App\Modules\Merchants\Models\Merchant;
use DB;
use Illuminate\Http\Request;

class MerchantsController extends ApiBaseController
{
    public function index(Request $request)
    {
        $query = Merchant::query()
            ->with('merchant_info')
            ->filterRequest($request)
            ->latest();

        if ($request->query('object') == true) {
            return new MerchantsResource($query->first());
        }

        return MerchantsResource::collection($query->paginate($request->query('per_page') ?? 15));
    }

    public function indexSpecial(Request $request)
    {
        //Заказный роут от Рамиль ака
        $query = Merchant::query()
            ->select([
                DB::raw('group_concat(id) as merchant_ids'),
                'legal_name',
                'legal_name_prefix',
            ])
            ->filterRequest($request)
            ->groupBy('legal_name', 'legal_name_prefix');

        if ($request->query('object') == true) {
            return new  SpecialMerchantResource($query->first());
        }

        return SpecialMerchantResource::collection($query->paginate($request->query('per_page') ?? 15));
    }
}
