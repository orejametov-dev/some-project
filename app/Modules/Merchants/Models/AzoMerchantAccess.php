<?php

declare(strict_types=1);

namespace App\Modules\Merchants\Models;

use App\Filters\AzoMerchantAccess\AzoMerchantAccessFilters;
use App\Traits\SortableByQueryParams;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

/**
 * App\Modules\Merchants\Models\MerchantUser.
 *
 * @property int $id
 * @property int $merchant_id
 * @property int $store_id
 * @property int $user_id
 * @property string $user_name
 * @property string $phone
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Merchant $merchant
 * @property-read Store $store
 * @method static Builder|AzoMerchantAccess byMerchant($merchant_id)
 * @method static Builder|AzoMerchantAccess byStore($store_id)
 * @method static Builder|AzoMerchantAccess byUserId($user_id)
 * @method static Builder|Condition filterRequest(Request $request, array $filters = [])
 * @method static Builder|AzoMerchantAccess newModelQuery()
 * @method static Builder|AzoMerchantAccess newQuery()
 * @method static Builder|AzoMerchantAccess orderRequest(Request $request, string $default_order_str = 'id:desc')
 * @method static Builder|AzoMerchantAccess query()
 * @property int|null $company_user_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static Builder|AzoMerchantAccess byActiveMerchant()
 * @method static Builder|AzoMerchantAccess byActiveStore()
 * @method static Builder|AzoMerchantAccess filerRequest(Request $request, array $filters = [])
 * @method static \Illuminate\Database\Query\Builder|AzoMerchantAccess onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|AzoMerchantAccess withTrashed()
 * @method static \Illuminate\Database\Query\Builder|AzoMerchantAccess withoutTrashed()
 */
class AzoMerchantAccess extends Model
{
    use HasFactory;
    use SortableByQueryParams;
    use SoftDeletes;

    protected $table = 'azo_merchant_accesses';
    protected $fillable = [
        'user_name',
        'phone',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function scopeByMerchant(Builder $query, int $merchant_id): Builder
    {
        return $query->where('merchant_id', $merchant_id);
    }

    public function scopeByActiveMerchant(Builder $query): Builder
    {
        return $query->whereHas('merchant', function ($query) {
            $query->where('active', true);
        });
    }

    public function scopeByActiveStore(Builder $query): Builder
    {
        return $query->whereHas('store', function ($query) {
            $query->where('active', true);
        });
    }

    public function scopeByStore(Builder $query, int $store_id): Builder
    {
        return $query->where('store_id', $store_id);
    }

    public function scopeByUserId(Builder $query, int $user_id): Builder
    {
        return $query->where('user_id', $user_id);
    }

    public function scopeFilterRequest(Builder $builder, Request $request, array $filters = []): Builder
    {
        return (new AzoMerchantAccessFilters($request, $builder))->execute($filters);
    }
}
