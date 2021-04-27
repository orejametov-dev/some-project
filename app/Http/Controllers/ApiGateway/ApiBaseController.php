<?php


namespace App\Http\Controllers\ApiGateway;


use App\Http\Controllers\Controller;
use App\Services\User;

class ApiBaseController extends Controller
{
    protected $user;
    protected $prm_admin;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $input = $request->header('x-auth_user');
            $this->user = app(User::class);
            $user = is_array($input) ? $input : json_decode($input,true);
            if(array_key_exists('prm_admin', $input)) $this->prm_admin = $user['prm_admin'];
            return $next($request);
        });
    }
}
