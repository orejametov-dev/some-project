<?php


namespace App\Http\Controllers\ApiMerchantGateway;


use App\Http\Controllers\Controller;
use App\Services\User;

class ApiBaseController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = app(User::class);
            if($merchant_user = optional($this->user)->merchant_user) {
                $request->request->add([
                    'merchant_id' => $merchant_user->merchant_id,
                    'store_id' => $merchant_user->store_id
                ]);
            }
            return $next($request);
        });
    }
}
