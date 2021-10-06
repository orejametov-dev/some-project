<?php

namespace App\Modules\AlifshopMerchant\Traits;

use App\Modules\AlifshopMerchants\Models\AlifshopMerchant;

trait AlifshopMerchantStoreRelationshipsTrait
{
    public function alifshopMerchant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AlifshopMerchant::class);
    }
}
