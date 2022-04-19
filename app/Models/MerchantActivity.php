<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $merchant_id
 * @property int $activity_reason_id
 * @property bool $active
 * @property int $created_by_id
 * @property string $created_by_name
 * @method static Builder|MerchantActivity maxActivityId()
 */
class MerchantActivity extends Model
{
    use HasFactory;

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }
}
