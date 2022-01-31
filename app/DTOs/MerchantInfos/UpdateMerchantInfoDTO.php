<?php


namespace App\DTOs\MerchantInfos;


class UpdateMerchantInfoDTO
{
    public function __construct(
        public string $director_name,
        public string $phone,
        public string $vat_number,
        public string $mfo,
        public string $tin,
        public string $oked,
        public string $bank_account,
        public string $bank_name,
        public string $address,
    )
    {
    }
}
