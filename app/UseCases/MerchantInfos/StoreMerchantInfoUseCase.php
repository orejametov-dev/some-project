<?php

declare(strict_types=1);

namespace App\UseCases\MerchantInfos;

use App\DTOs\MerchantInfos\StoreMerchantInfoDTO;
use App\Exceptions\BusinessException;
use App\Models\MerchantInfo;
use App\UseCases\Merchants\FindMerchantByIdUseCase;

class StoreMerchantInfoUseCase
{
    public function __construct(
        private FindMerchantByIdUseCase $findMerchantUseCase
    ) {
    }

    public function execute(StoreMerchantInfoDTO $storeMerchantInfoDTO): MerchantInfo
    {
        $merchant = $this->findMerchantUseCase->execute($storeMerchantInfoDTO->getMerchantId());

        if (MerchantInfo::query()->where('merchant_id', $merchant->id)->exists() === true) {
            throw new BusinessException('Партнер уже имеет основной договор');
        }

        $merchantInfo = MerchantInfo::fromDTO($storeMerchantInfoDTO);
        $merchantInfo->save();

        return $merchantInfo;
    }
}
