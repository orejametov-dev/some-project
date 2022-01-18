<?php


namespace App\Http\Controllers\ApiMerchantGateway;


use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\AzoMerchantAccess;
use App\Services\User;
use Illuminate\Support\Facades\Cache;

class ApiBaseController extends Controller
{
    protected $user;
    protected $store_id;
    protected $merchant_id;
    protected $azo_merchant_access;

    public function __construct()

    {
        $this->middleware(function ($request, $next) {
            $this->user = app(User::class);
            $this->azo_merchant_access = Cache::tags('azo_merchants')->remember('azo_merchant_user_id_' . $this->user->id, 86400, function () {
                $azo_merchant_access = AzoMerchantAccess::query()->with(['merchant', 'store'])
                    ->byActiveMerchant()
                    ->byActiveStore()
                    ->byUserId($this->user->id)->first();
                return $azo_merchant_access;
            });
            if (!$this->azo_merchant_access) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }
            $this->merchant_id = $this->azo_merchant_access->merchant_id;
            $this->store_id = $this->azo_merchant_access->store_id;
            return $next($request);
        });
    }
}
