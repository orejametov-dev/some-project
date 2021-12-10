<?php

namespace App\Modules\Merchants\Traits;

use App\Modules\Merchants\Models\ActivityReason;
use App\Modules\Merchants\Models\AdditionalAgreement;
use App\Modules\Merchants\Models\AzoMerchantAccess;
use App\Modules\Merchants\Models\Competitor;
use App\Modules\Merchants\Models\Condition;
use App\Modules\Merchants\Models\File;
use App\Modules\Merchants\Models\MerchantInfo;
use App\Modules\Merchants\Models\Store;
use App\Modules\Merchants\Models\Tag;

trait MerchantRelationshipsTrait
{
    public function stores()
    {
        return $this->hasMany(Store::class)->where('is_azo', true);
    }

    public function azo_merchant_accesses()
    {
        return $this->hasMany(AzoMerchantAccess::class);
    }

    public function application_conditions()
    {
        return $this->hasMany(Condition::class);
    }

    public function application_active_conditions()
    {
        return $this->hasMany(Condition::class)->where('active', true);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'merchant', 'merchant_tag', 'merchant_id', 'tag_id');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'merchant_id', 'id');
    }

    public function merchant_info()
    {
        return $this->hasOne(MerchantInfo::class);
    }

    public function additional_agreements()
    {
        return $this->hasMany(AdditionalAgreement::class);
    }

    public function activity_reasons()
    {
        return $this->morphToMany(ActivityReason::class, 'merchant', 'merchant_activities', 'merchant_id', 'activity_reason_id')->withTimestamps();
    }

    public function competitors()
    {
        return $this->belongsToMany(Competitor::class , 'merchant_competitor')->withPivot('volume_sales', 'percentage_approve' , 'partnership_at')->withTimestamps();;
    }

}
