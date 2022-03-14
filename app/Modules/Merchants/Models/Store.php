<?php

declare(strict_types=1);

namespace App\Modules\Merchants\Models;

use App\Filters\Store\StoreFilters;
use App\Traits\SortableByQueryParams;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\Request;
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
 * @property string[] $filable
 * @property int $is_archived
 * @property bool $active
 * @property string|null $district
 * @property-read Collection|ActivityReason[] $activity_reasons
 * @property-read int|null $activity_reasons_count
 * @property-read Collection|Condition[] $conditions
 * @property-read int|null $conditions_count
 * @property-read Collection|Notification[] $notifications
 * @property-read int|null $notifications_count
 * @method static Builder|Store newModelQuery()
 * @method static Builder|Store newQuery()
 * @method static Builder|Store orderRequest(Request $request, string $default_order_str = 'id:desc')
 * @method static Builder|Store query()
 * @method static Builder|Store active()
 * @method static Builder|Store byMerchant($merchant_id)
 * @method static Builder|Store filterRequest(\Illuminate\Http\Request $request, array $filters = [])
 * @method static Builder|Store main()
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

    public function scopeMain(Builder $query): Builder
    {
        return $query->where('is_main', true);
    }

    public function scopeByMerchant(Builder $query, int $merchant_id): Builder
    {
        return $query->where('merchant_id', $merchant_id);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeFilterRequest(Builder $builder, Request $request, array $filters = []): Builder
    {
        return (new StoreFilters($request, $builder))->execute($filters);
    }
}
