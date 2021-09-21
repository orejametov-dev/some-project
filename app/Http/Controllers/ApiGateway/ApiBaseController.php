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
            return $next($request);
        });
    }
}
