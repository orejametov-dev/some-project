<?php

declare(strict_types=1);

namespace App\Models;

use App\Filters\Condition\ConditionFilters;
use App\Traits\SortableByQueryParams;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;

/**
 * Class ApplicationCondition.
 *
 * @property int $id
 * @property bool $active
 * @property int $duration
 * @property int $commission
 * @property int $discount
 * @property bool $is_promotional
 * @property string $special_offer
 * @property int $merchant_id
 * @property int $store_id
 * @property int $event_id
 * @property bool $is_special
 * @property bool $post_merchant
 * @property bool $post_alifshop
 * @property Merchant $merchant
 * @property Store $store
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $started_at
 * @property Carbon|null $finished_at
 * @property-read mixed $title
 * @property-read Collection|Store[] $stores
 * @property-read int|null $stores_count
 * @method static Builder|Condition newModelQuery()
 * @method static Builder|Condition newQuery()
 * @method static Builder|Condition orderRequest(Request $request, string $default_order_str = 'id:desc')
 * @method static Builder|Condition query()
 * @method static Builder|Condition active()
 * @method static Builder|Condition byMerchant($merchant_id)
 * @method static Builder|Condition filterRequest(\Illuminate\Http\Request $request, array $filters = [])
 * @method static Builder|Condition postMerchant()
 */
class Condition extends Model
{
    use HasFactory;

    use SortableByQueryParams;

    protected $table = 'application_conditions';
    protected $fillable = [
        'duration',
        'commission',
        'active',
        'discount',
        'special_offer', // should be unique by partner
        'post_merchant',
        'post_alifshop',
        'started_at',
        'finished_at',
    ];
    protected $appends = ['title'];

    //Relationships
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class, 'special_store_conditions', 'condition_id', 'store_id');
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function scopeActive(Builder $builder): Builder
    {
        return $builder->where('active', true);
    }

    public function scopePostMerchant(Builder $builder): Builder
    {
        return $builder->where('post_merchant', true);
    }

    public function getTitleAttribute(): string
    {
        return $this->duration . 'м' . ' / ' . $this->commission . '%';
    }

    public function scopeByMerchant(Builder $query, int $merchant_id): Builder
    {
        return $query->where('merchant_id', $merchant_id);
    }

    public function scopeFilterRequest(Builder $builder, Request $request, array $filters = []): Builder
    {
        return (new ConditionFilters($request, $builder))->execute($filters);
    }
}
