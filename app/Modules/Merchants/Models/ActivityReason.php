<?php

namespace App\Modules\Merchants\Models;

use App\Modules\AlifshopMerchants\Models\AlifshopMerchant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityReason extends Model
{
    use HasFactory;
    const MERCHANT_AUTO_DEACTIVATION_REASON_ID = 21;
    const STORE_AUTO_DEACTIVATION_REASON_ID = 22;

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_activities');
    }

    public function merchants()
    {
        return $this->morphedByMany(Merchant::class, 'merchant','merchant_activities', 'activity_reason_id', 'merchant_id');
    }

    public function alifshop_merchants()
    {
        return $this->morphedByMany(Merchant::class, 'merchant','merchant_activities', 'activity_reason_id', 'merchant_id');
    }

}
