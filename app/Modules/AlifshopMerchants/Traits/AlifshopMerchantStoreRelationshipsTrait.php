<?php

namespace App\Modules\AlifshopMerchant\Traits;

use App\Modules\AlifshopMerchants\Models\AlifshopMerchant;

trait AlifshopMerchantStoreRelationshipsTrait
{
    public function alfishopMerchant()
    {
        return $this->belongsTo(AlifshopMerchant::class);
    }
}
