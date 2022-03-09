<?php

namespace App\Modules\Merchants\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * @method static Builder|MerchantActivity maxActivityId()
 */
class MerchantActivity extends Model
{
    use HasFactory;

    protected $table = 'merchant_activities';

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function scopeMaxActivityId($query)
    {
        return $query->select(
            'activity_reasons.body',
            'merchant_activities.merchant_id',
            DB::raw('MAX(merchant_activities.id) as merchant_activities_id')
        )->join(
            'activity_reasons',
            'merchant_activities.activity_reason_id',
            '=',
            'activity_reasons.id'
        )->groupBy('merchant_activities.merchant_id', 'activity_reasons.body');
    }
}
