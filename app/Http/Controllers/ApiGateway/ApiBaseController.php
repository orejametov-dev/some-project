<?php


namespace App\Http\Controllers\ApiGateway;


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
                $request->request->add(['merchant_id' => $merchant_user->merchant_id]);
            }
            return $next($request);
        });
    }
}
