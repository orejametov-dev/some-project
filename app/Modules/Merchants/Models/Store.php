<?php

namespace App\Modules\Merchants\Models;

use App\Modules\Merchants\QueryBuilders\StoreQueryBuilder;
use App\Traits\SortableByQueryParams;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

/**
 * Class Store.
 *
 * @property int $id
 * @property string $name
 * @property bool $is_main
 * @property string $phone
 * @property string $address
 * @property string $region
 * @property float $lat
 * @property float $long
 * @property string $responsible_person
 * @property string $responsible_person_phone
 * @property int $merchant_id
 * @property string $client_type_register
 * @property Merchant $merchant
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Condition[] $application_conditions
 * @property-read int|null $application_conditions_count
 * @method static StoreQueryBuilder query()
 * @property string[] $fillable
 */
class Store extends Model
{
    use HasFactory;
    use SortableByQueryParams;

    protected $table = 'stores';

    protected $fillable = [
        'name',
        'is_main',
        'phone',
        'address',
        'region',
        'lat',
        'long',
        'responsible_person',
        'responsible_person_phone',
        'active',
        'district',
        'client_type_register',
    ];

    /**
     * @param Builder $query
     * @return StoreQueryBuilder
     */
    public function newEloquentBuilder($query)
    {
        return new StoreQueryBuilder($query);
    }

    public function notifications(): BelongsToMany
    {
        return $this->belongsToMany(Notification::class, 'store_notification', 'store_id', 'notification_id');
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function application_conditions(): HasMany
    {
        return $this->hasMany(Condition::class);
    }

    public function activity_reasons(): MorphToMany
    {
        return $this->morphToMany(ActivityReason::class, 'store', 'store_activities')->withTimestamps();
    }

    public function conditions(): BelongsToMany
    {
        return $this->belongsToMany(Condition::class, 'special_store_conditions', 'store_id', 'condition_id');
    }
}
