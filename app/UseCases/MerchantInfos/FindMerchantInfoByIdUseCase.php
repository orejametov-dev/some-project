<?php

declare(strict_types=1);

namespace App\UseCases\MerchantInfos;

use App\Exceptions\NotFoundException;
use App\Models\MerchantInfo;

class FindMerchantInfoByIdUseCase
{
    public function execute(int $id): MerchantInfo
    {
        $merchant_info = MerchantInfo::query()->find($id);
        if ($merchant_info === null) {
            throw new NotFoundException('Основной договор не найден');
        }

        return $merchant_info;
    }
}
