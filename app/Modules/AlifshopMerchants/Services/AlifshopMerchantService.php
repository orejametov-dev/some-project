<?php

namespace App\Modules\AlifshopMerchants\Services;

use  App\Modules\AlifshopMerchants\DTO\AlifshopMerchantDTO;
use App\Modules\AlifshopMerchants\Models\AlifshopMerchant;

class AlifshopMerchantService
{
    public function create(AlifshopMerchantDTO $alifshopMerchantDTO)
    {
        $alifshop_merchant =  new AlifshopMerchant();
        $alifshop_merchant->maintainer_id = $alifshopMerchantDTO->maintainer_id;
        $alifshop_merchant->company_id = $alifshopMerchantDTO->company_id;
        $alifshop_merchant->save();

        return $alifshop_merchant;
    }
}
