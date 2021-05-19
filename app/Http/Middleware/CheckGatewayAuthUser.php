<?php

namespace App\Http\Middleware;

use App\Exceptions\BusinessException;
use App\Services\User;
use Closure;

class CheckGatewayAuthUser
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $auth_user = $request->header('x-auth-user');

        if(config('app.env') != 'production') {
            $auth_user = json_encode([
                'id' => 6,
                'name' => 'Oybek Rejametov',
                'avatar_link' => null,
                'phone' => '998998921652'
            ]);
        }

        if (!$auth_user) {
            throw new BusinessException('Unauthenticated', 401);
        }

        $auth_user = json_decode($auth_user);
        app()->instance(User::class, $auth_user);
        return $next($request);
    }
}
