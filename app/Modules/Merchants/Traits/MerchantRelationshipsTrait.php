<?php

namespace App\Modules\Merchants\Traits;

use App\Modules\Companies\Models\Company;
use App\Modules\Merchants\Models\ActivityReason;
use App\Modules\Merchants\Models\AdditionalAgreement;
use App\Modules\Merchants\Models\AzoMerchantAccess;
use App\Modules\Merchants\Models\Condition;
use App\Modules\Merchants\Models\File;
use App\Modules\Merchants\Models\MerchantInfo;
use App\Modules\Merchants\Models\Store;
use App\Modules\Merchants\Models\Tag;

trait MerchantRelationshipsTrait
{
    public function stores()
    {
        return $this->hasMany(Store::class);
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
        return $this->belongsToMany(Tag::class, 'merchant_tag', 'merchant_id', 'tag_id');
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
        return $this->belongsToMany(ActivityReason::class, 'merchant_activities')->withTimestamps();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
