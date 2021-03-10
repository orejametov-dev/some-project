<?php

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;

class GatewayAccessMiddleware
{
    use ResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $requestToken = $request->header('Gateway-Access');
        $token = config('local_services.gateway_access_secret');

        if ($requestToken !== $token) {
            return $this->errorResponse('Invalid Gateway Access', 500);
        }

        return $next($request);
    }
}
