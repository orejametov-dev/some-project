<?php


namespace App\Http\Controllers\ApiGate\Merchants;


use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\MerchantUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MerchantUsersController extends Controller
{
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
