<?php


namespace App\Http\Controllers\ApiMerchantGateway;


use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\MerchantUser;
use App\Services\User;
use Illuminate\Support\Facades\Cache;

class ApiBaseController extends Controller
{
    protected $user;
    protected $store_id;
    protected $merchant_id;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = app(User::class);
            $merchant_user = Cache::tags('merchants')->remember('merchant_user_id_' . $this->user->id, 86400, function () {
                $merchant_user = MerchantUser::query()->with(['merchant', 'store'])
                    ->byActiveMerchant()
                    ->byActiveStore()
                    ->byUserId($this->user->id)->first();
                return $merchant_user;
            });
            $this->merchant_id = $merchant_user->merchant_id;
            $this->store_id = $merchant_user->store_id;
            return $next($request);
        });
    }
}
