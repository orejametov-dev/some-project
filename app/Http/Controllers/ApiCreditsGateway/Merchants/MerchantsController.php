<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiCreditsGateway\Merchants;

use App\Filters\Merchant\QMerchantFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiCredtisGateway\Merchants\MerchantsResource;
use App\Http\Resources\ApiCredtisGateway\Merchants\SpecialMerchantResource;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MerchantsController extends Controller
{
    public function index(Request $request)
    {
        $query = Merchant::query()
            ->with('merchant_info')
            ->filterRequest($request, [QMerchantFilter::class])
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
            ->filterRequest($request, [QMerchantFilter::class])
            ->groupBy('legal_name', 'legal_name_prefix');

        if ($request->query('object') == true) {
            return new  SpecialMerchantResource($query->first());
        }

        return SpecialMerchantResource::collection($query->paginate($request->query('per_page') ?? 15));
    }
}
