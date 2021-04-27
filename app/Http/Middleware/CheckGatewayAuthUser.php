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
        $auth_user = $request->header('x-auth_user');

        if (!$auth_user) {
            throw new BusinessException('Unauthenticated', 401);
        }

        if (is_string($auth_user)) {
            $auth_user = json_decode($auth_user, true);

            if (!array_key_exists('avatar_link', $auth_user)) {
                $auth_user['avatar_link'] = null;
            }
        } else if (is_array($auth_user)) {


            if (!array_key_exists('avatar_link', $auth_user)) {
                $auth_user['avatar_link'] = null;
            }
        }
        $user = new User($auth_user);
        app()->instance(User::class, $user);
        return $next($request);
    }
}
