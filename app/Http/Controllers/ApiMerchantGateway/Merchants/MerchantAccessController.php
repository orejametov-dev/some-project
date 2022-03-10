<?php

namespace App\Http\Controllers\ApiMerchantGateway\Merchants;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiMerchantGateway\MerchantAccess\AccessMerchantCheckToActiveMerchantResource;
use App\Modules\Merchants\Models\AzoMerchantAccess;

class MerchantAccessController extends Controller
{
    public function checkToActiveMerchant(GatewayAuthUser $gatewayAuthUser)
    {
        $azo_merchant_access = AzoMerchantAccess::query()
            ->where('user_id', $gatewayAuthUser->getId())
            ->first();

        if ($azo_merchant_access === null) {
            throw new BusinessException('Сотрудник не найден', 'object_not_found', 404);
        }

        return new AccessMerchantCheckToActiveMerchantResource($azo_merchant_access);
    }
}
