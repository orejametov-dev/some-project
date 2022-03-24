<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiMerchantGateway\MerchantAccess;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Models\AzoMerchantAccess;
use Illuminate\Support\Facades\Cache;

class MerchantAccessController extends Controller
{
    public function checkToActiveMerchant(GatewayAuthUser $gatewayAuthUser)
    {
        return Cache::tags('azo_merchants')->remember('active_merchant_by_user_id_' . $gatewayAuthUser->getId(), 86400, function () use ($gatewayAuthUser) {
            $azo_merchant_access = AzoMerchantAccess::query()
                ->where('user_id', $gatewayAuthUser->getId())
                ->first();

            if ($azo_merchant_access === null) {
                throw new BusinessException('Сотрудник не найден', 'object_not_found', 404);
            }

            return response()->json([
                'active' => (bool) $azo_merchant_access->merchant->active == true && $azo_merchant_access->store->active == true,
            ]);
        });
    }
}
