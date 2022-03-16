<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiMerchantGateway\Merchants;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\DTOs\Auth\AzoAccessDto;
use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\AzoMerchantAccess;
use App\Modules\Merchants\Models\Condition;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Store;
use Illuminate\Support\Facades\Cache;

class MerchantsController extends Controller
{
    //роут для фронт мерчанта
    public function getMerchantDetailsWithRelations(AzoAccessDto $azoAccessDto, GatewayAuthUser $gatewayAuthUser)
    {
        $merchant = Cache::tags($azoAccessDto->merchant_id)->remember('cache_of_merchant', 60 * 60, function () use ($azoAccessDto) {
            return Merchant::findOrFail($azoAccessDto->merchant_id);
        });

        $conditions = Cache::tags($azoAccessDto->merchant_id)->remember('cache_of_merchant_conditions', 60 * 60, function () use ($merchant) {
            return Condition::query()
                ->active()
                ->byMerchant($merchant->id)
                ->where('post_merchant', true)
                ->get();
        });

        $stores = Cache::tags($azoAccessDto->merchant_id)->remember('cache_of_merchant_stores', 60 * 60, function () use ($merchant) {
            return Store::query()
                ->byMerchant($merchant->id)->get();
        });

        $store = Cache::tags($azoAccessDto->merchant_id)->remember(
            $azoAccessDto->id . 'detail_cache_of_merchant_stores',
            60 * 60,
            function () use ($gatewayAuthUser) {
                $azo_merchant_access = AzoMerchantAccess::query()->byUserId($gatewayAuthUser->getId())->firstOrFail();

                return Store::query()
                    ->findOrFail($azo_merchant_access->store_id);
            }
        );

        return [
            'merchant' => $merchant,
            'conditions' => $conditions,
            'stores' => $stores,
            'store' => $store,
        ];
    }
}
