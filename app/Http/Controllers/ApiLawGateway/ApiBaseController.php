<?php

namespace App\Http\Controllers\ApiLawGateway;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\Http\Controllers\Controller;
use App\Services\User;
use Illuminate\Http\Request;

class ApiBaseController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = app(GatewayAuthUser::class);
            return $next($request);
        });
    }
}
