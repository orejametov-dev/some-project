<?php


namespace App\Http\Controllers\ApiMerchantGateway;


use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
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
            $merchant_user = Cache::tags('merchants')->get('merchant_user_id_' . $this->user->id);
            if (!$merchant_user) {
                throw new BusinessException('Unauthenticated', 401);
            }
            $this->merchant_id = $merchant_user->id;
            $this->store_id = $merchant_user->store_id;
            return $next($request);
        });
    }
}
