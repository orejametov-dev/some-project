<?php

declare(strict_types=1);

namespace App\UseCases\MerchantInfos;

use App\DTOs\MerchantInfos\StoreMerchantInfoDTO;
use App\Exceptions\BusinessException;
use App\Modules\Merchants\Models\MerchantInfo;
use App\UseCases\Merchants\FindMerchantUseCase;

class StoreMerchantInfoUseCase
{
    public function __construct(
        private FindMerchantUseCase $findMerchantUseCase
    ) {
    }

    public function execute(StoreMerchantInfoDTO $storeMerchantInfoDTO): MerchantInfo
    {
        $merchant = $this->findMerchantUseCase->execute($storeMerchantInfoDTO->merchant_id);

        if (MerchantInfo::where('merchant_id', $merchant->id)->exists()) {
            throw new BusinessException('Партнер уже имеет основной договор');
        }

        $merchantInfo = MerchantInfo::fromDTO($storeMerchantInfoDTO);
        $merchantInfo->save();

        return $merchantInfo;
    }
}
