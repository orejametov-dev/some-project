<?php


namespace App\Http\Controllers\ApiComplianceGateway\Merchants;


use App\Http\Controllers\ApiComplianceGateway\ApiBaseController;
use App\Http\Resources\ApiComplianceGateway\Merchants\MerchantsResource;
use App\Modules\Merchants\Models\Merchant;
use Illuminate\Http\Request;

class MerchantsController extends ApiBaseController
{
    public function index(Request $request)
    {
        $merchants = Merchant::query()
            ->filterRequest($request)
            ->orderRequest($request);

        return MerchantsResource::collection($merchants->paginate($request->query('per_page')) ?? 15);
    }
}
