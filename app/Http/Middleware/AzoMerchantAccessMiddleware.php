<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\DTOs\Auth\AzoAccessDto;
use App\Exceptions\UnauthenticatedException;
use App\Models\AzoMerchantAccess;
use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AzoMerchantAccessMiddleware
{
    public function __construct(
        private Application $app,
        private GatewayAuthUser $gatewayAuthUser
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $azo_merchant_access = Cache::tags('azo_merchants')->remember('azo_merchant_user_id_' . $this->gatewayAuthUser->getId(), 86400, function () {
            return AzoMerchantAccess::query()->with(['merchant', 'store'])
                ->byActiveMerchant()
                ->byActiveStore()
                ->byUserId($this->gatewayAuthUser->getId())->first();
        });

        if (!$azo_merchant_access) {
            throw new UnauthenticatedException('Unauthenticated');
        }

        $azo_access_dto = AzoAccessDto::fromArray([
            'merchant_id' => $azo_merchant_access->merchant_id,
            'store_id' => $azo_merchant_access->store_id,
            'id' => $azo_merchant_access->id,
        ]);

        $this->app->instance(AzoAccessDto::class, $azo_access_dto);

        return $next($request);
    }
}
