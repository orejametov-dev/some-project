<?php

declare(strict_types=1);

namespace App\UseCases\MerchantInfos;

use App\Exceptions\BusinessException;
use App\Models\MerchantInfo;

class FindMerchantInfoByIdUseCase
{
    public function execute(int $id): MerchantInfo
    {
        $merchant_info = MerchantInfo::query()->find($id);
        if ($merchant_info === null) {
            throw new BusinessException('Основной договор не найден', 'object_not_found', 404);
        }

        return $merchant_info;
    }
}
