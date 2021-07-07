<?php


namespace App\Http\Controllers\ApiComplianceGateway\Merchants;


use App\Http\Controllers\ApiComplianceGateway\ApiBaseController;
use App\Http\Resources\ApiComplianceGateway\Merchants\MerchantsResource;
use App\Modules\Merchants\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MerchantsController extends ApiBaseController
{
    public function index(Request $request)
    {
        $merchants = Merchant::query()
            ->filterRequest($request)
            ->orderRequest($request);

        if ($request->has('paginate') && $request->query('paginate') == false) {
            return Cache::remember($request->fullUrl(), 600, function () use ($merchants) {
                return MerchantsResource::collection($merchants->get());
            });
        }

        return Cache::remember($request->fullUrl(), 180, function () use ($merchants, $request) {
            return MerchantsResource::collection($merchants->paginate($request->query('per_page')) ?? 15);
        });
    }
}
