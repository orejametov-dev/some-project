<?php

namespace App\Modules\Merchants\Services\Merchants;

use App\Modules\Merchants\DTO\Merchants\MerchantInfoDTO;
use App\Modules\Merchants\DTO\Merchants\MerchantsDTO;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\MerchantInfo;

class MerchantsService
{
    public function create(MerchantsDTO $merchantsDTO)
    {
        $merchant = new Merchant();
        $merchant->id = $merchantsDTO->id;
        $merchant->name = $merchantsDTO->name;
        $merchant->legal_name = $merchantsDTO->legal_name;
        $merchant->legal_name_prefix = $merchantsDTO->legal_name_prefix;
        $merchant->token = $merchantsDTO->token;
        $merchant->maintainer_id = $merchantsDTO->maintainer_id;
        $merchant->company_id = $merchantsDTO->company_id;
        $merchant->save();

        return $merchant;
    }

    public function createMerchantInfo(MerchantInfoDTO $merchantInfoDTO, Merchant $merchant)
    {
        $merchantInfo = new MerchantInfo();

        $merchantInfo->merchant_id = $merchant->id;
        $merchantInfo->legal_name = $merchantInfoDTO->legal_name;
        $merchantInfo->legal_name_prefix = $merchantInfoDTO->legal_name_prefix;
        $merchantInfo->director_name = $merchantInfoDTO->director_name;
        $merchantInfo->phone = $merchantInfoDTO->phone;
        $merchantInfo->vat_number = $merchantInfoDTO->vat_number;
        $merchantInfo->mfo = $merchantInfoDTO->mfo;
        $merchantInfo->tin = $merchantInfoDTO->tin;
        $merchantInfo->oked = $merchantInfoDTO->oked;
        $merchantInfo->bank_account = $merchantInfoDTO->bank_account;
        $merchantInfo->bank_name = $merchantInfoDTO->bank_name;
        $merchantInfo->address = $merchantInfoDTO->address;
        $merchantInfo->contract_number = MerchantInfo::getMaxContractNumber() + 1;
        $merchantInfo->contract_date = now();
        $merchantInfo->limit = MerchantInfo::LIMIT;
        $merchantInfo->save();

        return $merchantInfo;
    }
}
