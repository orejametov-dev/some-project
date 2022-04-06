<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGate\Merchants;

use App\Http\Controllers\Controller;
use App\Models\AzoMerchantAccess;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

class AzoMerchantAccesses extends Controller
{
    public function getByUserId(int $user_id): JsonResource
    {
        return Cache::tags('azo_merchants')->remember('azo_merchant_user_id_' . $user_id, 86400, function () use ($user_id) {
            $azo_merchant_access = AzoMerchantAccess::query()->with(['merchant', 'store'])
                ->byActiveMerchant()
                ->byActiveStore()
                ->byUserId($user_id)->first();

            return new JsonResource($azo_merchant_access);
        });
    }
}
