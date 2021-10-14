<?php

namespace App\Modules\AlifshopMerchants\Traits;


use App\Modules\Companies\Models\Company;
use App\Modules\Merchants\Models\ActivityReason;
use App\Modules\Merchants\Models\Store;
use App\Modules\Merchants\Models\Tag;

trait AlifshopMerchantRelationshipsTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     */
    public function stores()
    {
        return $this->hasMany(Store::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'merchant', 'merchant_tag', 'merchant_id', 'tag_id');
    }

    public function activity_reasons()
    {
        return $this->morphToMany(ActivityReason::class, 'merchant', 'merchant_activities', 'merchant_id', 'activity_reason_id')->withTimestamps();
    }
}
