<?php

declare(strict_types=1);

namespace App\UseCases\MerchantInfos;


use App\DTOs\MerchantInfos\StoreMerchantInfoDTO;
use App\Exceptions\BusinessException;
use App\Modules\Merchants\Models\MerchantInfo;
use App\UseCases\Merchants\FindMerchantUseCase;

class UpdateMerchantInfoUseCase
{
    public function execute(int $id, StoreMerchantInfoDTO $storeMerchantInfoDTO): MerchantInfo
    {
        $merchantInfo = MerchantInfo::query()->find($id);
        if($merchantInfo === null) {
            throw new BusinessException('Основной договор не найден', 'object_not_found', 404);
        }

        $merchantInfo->director_name = $storeMerchantInfoDTO->director_name;
        $merchantInfo->phone = $storeMerchantInfoDTO->phone;
        $merchantInfo->vat_number = $storeMerchantInfoDTO->vat_number;
        $merchantInfo->mfo = $storeMerchantInfoDTO->mfo;
        $merchantInfo->tin = $storeMerchantInfoDTO->tin;
        $merchantInfo->oked = $storeMerchantInfoDTO->oked;
        $merchantInfo->bank_account = $storeMerchantInfoDTO->bank_account;
        $merchantInfo->bank_name = $storeMerchantInfoDTO->bank_name;
        $merchantInfo->address = $storeMerchantInfoDTO->address;
        $merchantInfo->limit = MerchantInfo::LIMIT;
        $merchantInfo->save();

        return $merchantInfo;
    }
}
