<?php


namespace App\Http\Controllers\Api\Merchants;


use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\Merchant;
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

    public function show(Request $request, $id)
    {
        $this->validate($request, [
            'relations' => 'nullable|array'
        ]);

        $merchant = Merchant::query();

        if($request->query('relations')){
            $merchant->with($request->query('relations'));
        }

        return $merchant->findOrFail($id);
    }

    public function verifyToken(Request $request)
    {
        $isTokenValid = Merchant::query()->where('token', $request->token)->exists();

        return [
            'is_token_valid' => $isTokenValid
        ];
    }

}
