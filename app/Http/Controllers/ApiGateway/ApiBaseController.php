<?php

namespace App\Http\Controllers\ApiGateway;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\Http\Controllers\Controller;

class ApiBaseController extends Controller
{
    protected $user;

    public function __construct(
    ) {
        $this->middleware(function ($request, $next) {
            $this->user = app(GatewayAuthUser::class);

            return $next($request);
        });
    }
}
