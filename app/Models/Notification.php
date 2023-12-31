<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\NotificationTypeEnum;
use App\Filters\Notification\NotificationFilters;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;

/**
 * Class Notification.
 *
 * @property int $id
 * @property string $title_uz
 * @property string $title_ru
 * @property string $body_uz
 * @property string $body_ru
 * @property Carbon $start_schedule
 * @property Carbon $end_schedule
 * @property NotificationTypeEnum $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Notification filterRequest(Request $request, array $filters = [])
 * @method static Builder|Notification query()
 * @property int $created_by_id
 * @property string $created_by_name
 * @property-read Collection|Store[] $stores
 * @property-read int|null $stores_count
 * @method static Builder|Notification newModelQuery()
 * @method static Builder|Notification newQuery()
 * @method static Builder|Notification onlyByMerchant($merchant_id)
 * @method static Builder|Notification onlyByStore($store_id)
 * @method static Builder|Notification onlyMoreThanStartSchedule()
 */
class Notification extends Model
{
    use HasFactory;

    protected $casts = [
        'type' => NotificationTypeEnum::class,
    ];

    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class, 'store_notification', 'notification_id', 'store_id');
    }

    public function scopeOnlyByMerchant(Builder $query, int $merchant_id): Builder
    {
        return $query->whereHas('stores', function (Builder $query) use ($merchant_id) {
            $query->where('stores.merchant_id', $merchant_id);
        });
    }

    public function scopeOnlyByStore(Builder $query, int $store_id): Builder
    {
        return $query->whereHas('stores', function (Builder $query) use ($store_id) {
            $query->where('stores.id', $store_id);
        });
    }

    public function scopeOnlyMoreThanStartSchedule(Builder $query): Builder
    {
        return $query->where('start_schedule', '<=', now());
    }

    public function scopeFilterRequest(Builder $builder, Request $request, array $filters = []): Builder
    {
        return (new NotificationFilters($request, $builder))->execute($filters);
    }
}
