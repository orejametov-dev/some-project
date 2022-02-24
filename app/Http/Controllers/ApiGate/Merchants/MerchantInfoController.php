<?php

namespace App\Http\Controllers\ApiGate\Merchants;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiGate\MerchantInfos\MerchantInfoResource;
use App\Modules\Merchants\Models\MerchantInfo;

class MerchantInfoController extends Controller
{
    public function getMerchantInfoByMerchantId($merchant_id): MerchantInfoResource
    {
        $merchant_info = MerchantInfo::query()
            ->where('merchant_id', $merchant_id)
            ->orderByDesc('id')
            ->first();

        if ($merchant_info === null) {
            throw new BusinessException('Основной договор не найден', 'object_not_found', 404);
        }

        return new MerchantInfoResource($merchant_info);
    }
}
