<?php

namespace App\Modules\AlifshopMerchants\Traits;

use App\Modules\AlifshopMerchants\Models\AlifshopMerchant;

trait AlifshopMerchantStoreRelationshipsTrait
{
    public function alifshop_merchant()
    {
        return $this->belongsTo(AlifshopMerchant::class);
    }
}
