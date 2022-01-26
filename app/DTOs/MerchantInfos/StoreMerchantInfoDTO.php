<?php


namespace App\DTOs\MerchantInfos;


class StoreMerchantInfoDTO
{
    public function __construct(
        public int    $merchant_id,
        public string $director_name,
        public string $legal_name,
        public string $legal_name_prefix,
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
