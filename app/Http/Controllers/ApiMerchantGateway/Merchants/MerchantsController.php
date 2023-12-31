<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiMerchantGateway\Merchants;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\DTOs\Auth\AzoAccessDto;
use App\Http\Controllers\Controller;
use App\Models\AzoMerchantAccess;
use App\Models\Condition;
use App\Models\Store;
use App\UseCases\Merchants\FindMerchantByIdUseCase;
use Illuminate\Support\Facades\Cache;

class MerchantsController extends Controller
{
    //роут для фронт мерчанта
    public function getMerchantDetailsWithRelations(AzoAccessDto $azoAccessDto, GatewayAuthUser $gatewayAuthUser, FindMerchantByIdUseCase $findMerchantByIdUseCase): array
    {
        $merchant = Cache::tags($azoAccessDto->getMerchantId())->remember('cache_of_merchant', 60 * 60, function () use ($azoAccessDto, $findMerchantByIdUseCase) {
            return $findMerchantByIdUseCase->execute($azoAccessDto->getMerchantId());
        });

        $conditions = Cache::tags($azoAccessDto->getMerchantId())->remember('cache_of_merchant_conditions', 60 * 60, function () use ($merchant) {
            return Condition::query()
                ->active()
                ->byMerchant($merchant->id)
                ->where('post_merchant', true)
                ->get();
        });

        $stores = Cache::tags($azoAccessDto->getMerchantId())->remember('cache_of_merchant_stores', 60 * 60, function () use ($merchant) {
            return Store::query()
                ->byMerchant($merchant->id)->get();
        });

        $store = Cache::tags($azoAccessDto->getMerchantId())->remember(
            $azoAccessDto->getId() . 'detail_cache_of_merchant_stores',
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
