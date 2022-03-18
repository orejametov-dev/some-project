<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\ActivityReason.
 *
 * @property int $id
 * @property string $body
 * @property string $type
 * @property int $active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Merchant[] $merchants
 * @property-read int|null $merchants_count
 * @property-read Collection|Store[] $stores
 * @property-read int|null $stores_count
 * @method static Builder|ActivityReason newModelQuery()
 * @method static Builder|ActivityReason newQuery()
 * @method static Builder|ActivityReason query()
 */
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
