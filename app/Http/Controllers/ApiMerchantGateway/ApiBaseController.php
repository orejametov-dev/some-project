<?php


namespace App\Http\Controllers\ApiMerchantGateway;


use App\Http\Controllers\Controller;
use App\Services\User;

class ApiBaseController extends Controller
{
    protected $user;
    protected $store_id;
    protected $merchant_id;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = app(User::class);
            if($merchant_user = optional($this->user)->merchant_user) {
                $this->store_id = $merchant_user->store_id;
                $this->merchant_id = $merchant_user->merchant_id;
                $request->request->add([
                    'merchant_id' => $merchant_user->merchant_id,
                ]);
            }
            return $next($request);
        });
    }
}
