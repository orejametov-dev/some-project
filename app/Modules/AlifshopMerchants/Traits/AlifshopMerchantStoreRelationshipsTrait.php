<?php

namespace App\Modules\AlifshopMerchants\Traits;

use App\Modules\AlifshopMerchants\Models\AlifshopMerchant;
use App\Modules\Merchants\Models\ActivityReason;

trait AlifshopMerchantStoreRelationshipsTrait
{
    public function alifshop_merchant()
    {
        return $this->belongsTo(AlifshopMerchant::class);
    }


    public function activity_reasons()
    {
        return $this->morphToMany(ActivityReason::class, 'store', 'store_activities')->withTimestamps();
    }
}
