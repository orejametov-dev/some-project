<?php

namespace App\Modules\Merchants\Models;

use App\Traits\SortableByQueryParams;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * Class ApplicationCondition
 *
 * @package App\Modules\Applications\Models
 * @property int $id
 * @property bool $active
 * @property int $duration
 * @property int $commission
 * @property int $discount
 * @property bool $is_promotional
 * @property string $special_offer
 * @property int $merchant_id
 * @property int $store_id
 * @property boolean $is_special
 * @property Merchant $merchant
 * @property Store $store
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read mixed $title
 * @method static Builder|Condition active()
 * @method static Builder|Condition postMerchant()
 * @method static Builder|Condition filterRequest(Request $request)
 * @method static Builder|Condition newModelQuery()
 * @method static Builder|Condition newQuery()
 * @method static Builder|Condition orderRequest(Request $request, string $default_order_str = 'id:desc')
 * @method static Builder|Condition query()
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
        'special_offer',// should be unique by partner
        'post_merchant',
        'post_alifshop'
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

    public function scopeActive($builder)
    {
        return $builder->where('active', true);
    }

    public function scopePostMerchant($builder)
    {
        return $builder->where('post_merchant', true);
    }

    public function scopeFilterRequest(Builder $query, Request $request)
    {
        if ($condition_ids = $request->query('condition_ids')) {
            $condition_ids = explode(';', $condition_ids);
            $query->whereIn('id', $condition_ids);
        }
        if ($condition_id = $request->query('condition_id')) {
            $query->where('id', $condition_id);
        }

        if ($store_id = $request->store_id) {
            $query->where('store_id', $store_id);
        }
        if ($merchant_id = $request->merchant_id) {
            $query->where('merchant_id', $merchant_id);
        }
        if ($updated_at = $request->query('updated_at')) {
            $query->where('updated_at', $updated_at);
        }
        if ($discount = $request->query('discount')) {
            $query->where('discount', $discount);
        }
        if ($commission = $request->query('commission')) {
            $query->where('commission', $commission);
        }
        if ($duration = $request->query('duration')) {
            $query->where('duration', $duration);
        }
        if ($request->has('active')) {
            $query->where('active', $request->query('active'));
        }
        return $query;
    }

    public function getTitleAttribute()
    {
        return $this->duration . 'м' . ' / ' . $this->commission . '%';
    }

    public function scopeByMerchant(Builder $query, $merchant_id)
    {
        return $query->where('merchant_id', $merchant_id);
    }
}
