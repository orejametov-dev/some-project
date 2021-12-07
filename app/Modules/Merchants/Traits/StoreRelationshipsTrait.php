<?php

namespace App\Modules\Merchants\Traits;

use App\Modules\AlifshopMerchants\Models\AlifshopMerchant;
use App\Modules\Merchants\Models\ActivityReason;
use App\Modules\Merchants\Models\Condition;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Tag;

trait StoreRelationshipsTrait
{
    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function alifshop_merchant()
    {
        return $this->belongsTo(AlifshopMerchant::class, 'merchant_id');
    }

    public function application_conditions()
    {
        return $this->hasMany(Condition::class);
    }

    public function activity_reasons()
    {
        return $this->morphToMany(ActivityReason::class, 'store', 'store_activities')->withTimestamps();
    }

    public function conditions()
    {
        return $this->belongsToMany(Condition::class, 'special_store_conditions', 'store_id', 'condition_id');
    }
}
