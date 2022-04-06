<?php

namespace App\Http\Controllers\ApiGate\Merchants;

use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiGate\MerchantInfos\MerchantInfoResource;
use App\Models\MerchantInfo;

class MerchantInfoController extends Controller
{
    public function getMerchantInfoByMerchantId(int $merchant_id): MerchantInfoResource
    {
        $merchant_info = MerchantInfo::query()
            ->where('merchant_id', $merchant_id)
            ->orderByDesc('id')
            ->first();

        if ($merchant_info === null) {
            throw new NotFoundException('Основной договор не найден');
        }

        return new MerchantInfoResource($merchant_info);
    }
}
