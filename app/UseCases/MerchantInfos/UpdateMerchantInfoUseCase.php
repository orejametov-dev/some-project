<?php

declare(strict_types=1);

namespace App\UseCases\MerchantInfos;

use App\DTOs\MerchantInfos\UpdateMerchantInfoDTO;
use App\Models\MerchantInfo;

class UpdateMerchantInfoUseCase
{
    public function __construct(
        private FindMerchantInfoByIdUseCase $findMerchantInfoByIdUseCase
    ) {
    }

    public function execute(int $id, UpdateMerchantInfoDTO $updateMerchantInfoDTO): MerchantInfo
    {
        $merchant_info = $this->findMerchantInfoByIdUseCase->execute($id);

        $merchant_info->director_name = $updateMerchantInfoDTO->getDirectorName();
        $merchant_info->phone = $updateMerchantInfoDTO->getPhone();
        $merchant_info->vat_number = $updateMerchantInfoDTO->getVatNumber();
        $merchant_info->mfo = $updateMerchantInfoDTO->getMfo();
        $merchant_info->tin = $updateMerchantInfoDTO->getTin();
        $merchant_info->oked = $updateMerchantInfoDTO->getOked();
        $merchant_info->bank_account = $updateMerchantInfoDTO->getBankAccount();
        $merchant_info->bank_name = $updateMerchantInfoDTO->getBankName();
        $merchant_info->address = $updateMerchantInfoDTO->getAddress();

        $merchant_info->save();

        return $merchant_info;
    }
}
