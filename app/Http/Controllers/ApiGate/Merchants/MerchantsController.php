<?php


namespace App\Http\Controllers\ApiGate\Merchants;


use App\Http\Controllers\Controller;
use App\Http\Resources\ApiGate\Merchants\MerchantsResource;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MerchantsController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'relations' => 'nullable|array'
        ]);

        $merchants = Merchant::query()->filterRequest($request);

        if($request->query('relations')){
            $merchants->with($request->query('relations'));
        }

        if ($request->query('object') == 'true') {
            return $merchants->first();
        }

        if ($request->has('paginate') && $request->query('paginate') == false) {
            return Cache::remember($request->fullUrl(), 600, function () use ($merchants) {
                return $merchants->get();
            });
        }

        return Cache::remember($request->fullUrl(), 180, function () use ($merchants, $request) {
            return $merchants->paginate($request->query('per_page'));
        });

    }

    public function show($id)
    {
        $merchant_query = Merchant::with(['application_active_conditions']);
        if (preg_match('/^\d+$/', $id)) {
            $merchant = $merchant_query->findOrFail($id);
        } else {
            $merchant = $merchant_query->where('token', $id)->firstOrFail();
        }
        $merchant->store = Store::where('merchant_id', $merchant->id)->where('is_main', true)->first();
        return new MerchantsResource($merchant);
    }

    public function verifyToken(Request $request)
    {
        $merchant = Merchant::query()->where('token', $request->token)->firstOrFail();

        return [
            'name' => $merchant->name,
            'merchant_id' => $merchant->id
        ];
    }

}
