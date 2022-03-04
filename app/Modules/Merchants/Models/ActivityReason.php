<?php

namespace App\Modules\Merchants\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ActivityReason extends Model
{
    use HasFactory;
    const MERCHANT_AUTO_DEACTIVATION_REASON_ID = 21;
    const STORE_AUTO_DEACTIVATION_REASON_ID = 22;

    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class, 'store_activities');
    }

    public function merchants(): BelongsToMany
    {
        return $this->belongsToMany(Merchant::class, 'merchant_activities', 'activity_reason_id', 'merchant_id');
    }
}
