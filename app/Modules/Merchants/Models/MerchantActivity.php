<?php

namespace App\Modules\Merchants\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
