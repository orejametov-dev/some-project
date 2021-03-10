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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (config('app.env') === 'production') {
            $auth_user = $request->input('auth_user');
            if (!$auth_user) {
                throw new BusinessException('Unauthenticated', 401);
            }

            if (is_string($auth_user)) {
                $auth_user = json_decode($auth_user, true);
                if(!$auth_user['prm_admin']) {
                    throw new BusinessException('Unauthenticated', 401);
                }

                if(!array_key_exists('avatar_link', $auth_user)){
                    $auth_user['avatar_link'] = null;
                }
            } else if(is_array($auth_user)) {

                if(!$auth_user['prm_admin']) {
                    throw new BusinessException('Unauthenticated', 401);
                }

                if(!array_key_exists('avatar_link', $auth_user)){
                    $auth_user['avatar_link'] = null;
                }
            }
        } else {
            $auth_user = [
                'id' => 1,
                'name' => 'asd',
                'phone' => '998998921652',
                'created_at' => 'sadas',
                'avatar_link' => null,
                'prm_admin' => [
                    'id' => 6,
                    'user_id' => 6
                ]
            ];
        }
        $user = new User($auth_user);
        app()->instance(User::class, $user);

        return $next($request);
    }
}
