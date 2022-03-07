<?php

namespace App\Http\Controllers\ApiComplianceGateway\Merchants;

use App\Filters\Merchant\QMerchantFilter;
use App\Filters\Merchant\SpecialMerchantIdFilter;
use App\Http\Controllers\ApiComplianceGateway\ApiBaseController;
use App\Http\Resources\ApiComplianceGateway\Merchants\MerchantsResource;
use App\Modules\Merchants\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MerchantsController extends ApiBaseController
{
    public function index(Request $request)
    {
        return Cache::tags('merchant_index')->remember($request->fullUrl(), 600, function () use ($request) {
            $merchantsQuery = Merchant::query()
                ->filterRequest($request, [
                    QMerchantFilter::class,
                    SpecialMerchantIdFilter::class,
                ])
                ->orderRequest($request);

            return MerchantsResource::collection($merchantsQuery->paginate($request->query('per_page') ?? 15));
        });
    }
}
