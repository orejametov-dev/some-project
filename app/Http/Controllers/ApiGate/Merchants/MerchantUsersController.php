<?php


namespace App\Http\Controllers\ApiGate\Merchants;


use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\MerchantUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MerchantUsersController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'relations' => 'nullable|array'
        ]);

        $merchant_users = MerchantUser::query()->filterRequest($request);

        if($request->query('relations')) {
            $merchant_users->with($request->query('relations'));
        }

        if ($request->query('object') == 'true') {
            return $merchant_users->first();
        }
        return $merchant_users->paginate($request->query('per_page'));
    }

    public function show(Request $request, $id)
    {
        $this->validate($request, [
            'relations' => 'nullable|array'
        ]);

        $merchant_user = MerchantUser::query()->filterRequest($request);

        if($request->input('relations')) {
            $merchant_user->with($request->input('relations'));
        }

        return $merchant_user->findOrFail($id);
    }

    public function getByUserId($user_id)
    {
        return Cache::tags('merchants')->remember('merchant_user_id_' . $user_id , 86400, function () use ($user_id) {
            $merchant_user = MerchantUser::query()->with(['merchant', 'store'])
                ->byActiveMerchant()
                ->byActiveStore()
                ->byUserId($user_id)->first();
            return $merchant_user;
        });
    }

}
