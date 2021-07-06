<?php

namespace App\Modules\Merchants\Traits;

use App\Modules\Merchants\Models\AdditionalAgreement;
use App\Modules\Merchants\Models\Condition;
use App\Modules\Merchants\Models\File;
use App\Modules\Merchants\Models\MerchantInfo;
use App\Modules\Merchants\Models\MerchantUser;
use App\Modules\Merchants\Models\Notification;
use App\Modules\Merchants\Models\Store;
use App\Modules\Merchants\Models\Tag;

trait MerchantRelationshipsTrait
{
    public function stores()
    {
        return $this->hasMany(Store::class);
    }

    public function merchant_users()
    {
        return $this->hasMany(MerchantUser::class);
    }

    public function application_conditions()
    {
        return $this->hasMany(Condition::class);
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
}
