<?php

namespace App\Modules\AlifshopMerchants\Traits;


use App\Modules\AlifshopMerchants\Models\AlifshopMerchantStores;
use App\Modules\Companies\Models\Company;

trait AlifshopMerchantRelationshipsTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     */
    public function alifshop_merchant_stores()
    {
        return $this->hasMany(AlifshopMerchantStores::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
