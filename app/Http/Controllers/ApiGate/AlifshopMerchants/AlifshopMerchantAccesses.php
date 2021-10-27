<?php

namespace App\Http\Controllers\ApiGate\AlifshopMerchants;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Modules\AlifshopMerchants\Models\AlifshopMerchantAccess;

class AlifshopMerchantAccesses extends Controller
{
    public function getByUserId($user_id)
    {
        return Cache::tags('alifshop_merchants')->remember('alifshop_merchant_user_id_' . $user_id, 86400, function () use ($user_id) {
            $alifshop_merchant_access = AlifshopMerchantAccess::query()
                ->whereHas('company_user' , function ($query) use ($user_id) {
                    $query->where('id' , $user_id);
                })
                ->byActiveMerchant()
                ->byActiveStore()
                ->first();

            return $alifshop_merchant_access;
        });
    }
}
