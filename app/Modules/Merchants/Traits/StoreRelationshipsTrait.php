<?php

namespace App\Modules\Merchants\Traits;

use App\Modules\Merchants\Models\ActivityReason;
use App\Modules\Merchants\Models\Condition;
use App\Modules\Merchants\Models\Merchant;

trait StoreRelationshipsTrait
{
    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
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
