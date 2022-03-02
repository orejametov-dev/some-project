<?php

namespace App\Modules\Merchants\Models;

use App\Modules\Merchants\QueryBuilders\ConditionQueryBuilder;
use App\Traits\SortableByQueryParams;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\Builder;

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
 * @method static ConditionQueryBuilder query()
 * @mixin Eloquent
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

    /**
     * @param Builder $query
     * @return ConditionQueryBuilder
     */
    public function newEloquentBuilder($query)
    {
        return new ConditionQueryBuilder($query);
    }

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

    public function getTitleAttribute()
    {
        return $this->duration . 'м' . ' / ' . $this->commission . '%';
    }
}
