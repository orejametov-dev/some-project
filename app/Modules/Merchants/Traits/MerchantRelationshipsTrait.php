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
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait MerchantRelationshipsTrait
{
    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }

    public function azo_merchant_accesses(): HasMany
    {
        return $this->hasMany(AzoMerchantAccess::class);
    }

    public function application_conditions(): HasMany
    {
        return $this->hasMany(Condition::class);
    }

    public function application_active_conditions(): HasMany
    {
        return $this->hasMany(Condition::class)->where('active', true);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'merchant', 'merchant_tag', 'merchant_id', 'tag_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'merchant_id', 'id');
    }

    public function merchant_info(): HasOne
    {
        return $this->hasOne(MerchantInfo::class);
    }

    public function additional_agreements(): HasMany
    {
        return $this->hasMany(AdditionalAgreement::class);
    }

    public function activity_reasons(): BelongsToMany
    {
        return $this->belongsToMany(ActivityReason::class, 'merchant_activities', 'merchant_id', 'activity_reason_id')->withTimestamps()
            ->withPivot(['id', 'merchant_id', 'activity_reason_id', 'active', 'created_by_id', 'created_by_name', 'created_at', 'updated_at']);
    }

    public function competitors(): BelongsToMany
    {
        return $this->belongsToMany(Competitor::class, 'merchant_competitor')->withPivot('volume_sales', 'percentage_approve', 'partnership_at')->withTimestamps();
    }
}
