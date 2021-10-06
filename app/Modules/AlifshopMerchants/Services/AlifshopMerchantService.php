<?php

namespace App\Modules\AlifshopMerchants\Services;

use  App\Modules\AlifshopMerchants\DTO\AlifshopMerchantDTO;
use App\Modules\AlifshopMerchants\Models\AlifshopMerchant;

class AlifshopMerchantService
{
    public function create(AlifshopMerchantDTO $alifshopMerchantDTO)
    {
        $alifshop_merchant =  new AlifshopMerchant();
        $alifshop_merchant->id = $alifshopMerchantDTO->id;
        $alifshop_merchant->name = $alifshopMerchantDTO->name;
        $alifshop_merchant->legal_name = $alifshopMerchantDTO->legal_name;
        $alifshop_merchant->token = $alifshopMerchantDTO->token;
        $alifshop_merchant->alifshop_slug = $alifshopMerchantDTO->alifshop_slug;
        $alifshop_merchant->information = $alifshopMerchantDTO->information;
        $alifshop_merchant->maintainer_id = $alifshopMerchantDTO->maintainer_id;
        $alifshop_merchant->company_id = $alifshopMerchantDTO->company_id;
        $alifshop_merchant->save();

        return $alifshop_merchant;
    }
}
