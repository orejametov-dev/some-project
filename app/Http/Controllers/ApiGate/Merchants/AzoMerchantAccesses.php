<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGate\Merchants;

use App\Http\Controllers\Controller;
use App\Models\AzoMerchantAccess;
use Illuminate\Support\Facades\Cache;

class AzoMerchantAccesses extends Controller
{
    public function getByUserId($user_id)
    {
        return Cache::tags('azo_merchants')->remember('azo_merchant_user_id_' . $user_id, 86400, function () use ($user_id) {
            $azo_merchant_access = AzoMerchantAccess::query()->with(['merchant', 'store'])
                ->byActiveMerchant()
                ->byActiveStore()
                ->byUserId($user_id)->first();

            return $azo_merchant_access;
        });
    }
}
