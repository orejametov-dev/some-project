<?php

namespace App\Modules\Merchants\Models;

use App\Traits\SortableByQueryParams;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

/**
 * Class ApplicationCondition
 * @package App\Modules\Applications\Models
 *
 * @property int $id
 * @property bool $active
 * @property int $duration
 * @property int $commission
 * @property int $discount
 * @property string $notice
 * @property bool $is_promotional
 * @property string $special_offer
 * @property int $merchant_id
 * @property int $store_id
 *
 * @property Merchant $merchant
 * @property Store $store
 */
class Condition extends Model
{
    use HasFactory;

    use SortableByQueryParams;
    protected $table = 'application_conditions';
    protected $fillable = [
        'duration',
        'notice',
        'commission',
        'active',
        'discount',
        'special_offer' // should be unique by partner
    ];
    protected $appends = ['title'];

    //Relationships
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function scopeActive($builder)
    {
        return $builder->where('active', true);
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

        if ($store_id = $request->query('store_id')) {
            $query->where('store_id', $store_id);
        }
        if ($merchant_id = $request->query('merchant_id')) {
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
}
