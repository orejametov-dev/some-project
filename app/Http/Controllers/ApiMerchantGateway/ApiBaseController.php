<?php


namespace App\Http\Controllers\ApiMerchantGateway;


use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\MerchantUser;
use App\Services\User;
use Illuminate\Support\Facades\Cache;

class ApiBaseController extends Controller
{
    protected $user;
    protected $store_id;
    protected $merchant_id;
    protected $merchant_user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = app(User::class);
            $this->merchant_user = Cache::tags('merchants')->remember('merchant_user_id_' . $this->user->id, 86400, function () {
                $merchant_user = MerchantUser::query()->with(['merchant', 'store'])
                    ->byActiveMerchant()
                    ->byActiveStore()
                    ->byUserId($this->user->id)->first();
                return $merchant_user;
            });
            if (!$this->merchant_user) {
                throw new BusinessException('Unauthenticated', 401);
            }
            $this->merchant_id = $this->merchant_user->merchant_id;
            $this->store_id = $this->merchant_user->store_id;
            return $next($request);
        });
    }
}
