<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AccessTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $requestToken = $request->header('Access-Token');
        $token = config('local_services.access_token');

        if ($requestToken) {
            return response()->json(['message' => 'Access token is required'], 500);
        }

        if ($requestToken !== $token) {
            return response()->json(['message' => 'Invalid access token'], 500);
        }

        return $next($request);
    }
}
