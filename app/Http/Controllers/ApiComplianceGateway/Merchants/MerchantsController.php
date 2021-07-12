<?php


namespace App\Http\Controllers\ApiComplianceGateway\Merchants;


use App\Http\Controllers\ApiComplianceGateway\ApiBaseController;
use App\Http\Resources\ApiComplianceGateway\Merchants\MerchantsResource;
use App\Modules\Merchants\Models\Merchant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MerchantsController extends ApiBaseController
{
    public function index(Request $request)
    {
        Log::channel('urgent')->debug('start_merchant_controller', [
            'time' => Carbon::now()
        ]);
        $merchants = Merchant::query()
            ->filterRequest($request)
            ->orderRequest($request);

        $result = Cache::remember($request->fullUrl(), 180, function () use ($merchants, $request) {
            return MerchantsResource::collection($merchants->paginate($request->query('per_page')) ?? 15);
        });

        Log::channel('urgent')->debug('end_merchant_controller', [
            'time' => Carbon::now()
        ]);

        return $result;
    }
}
