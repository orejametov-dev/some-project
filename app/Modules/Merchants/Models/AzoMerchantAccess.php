<?php

namespace App\Modules\Merchants\Models;

use App\Traits\SortableByQueryParams;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Merchant $merchant
 * @property-read Store $store
 * @method static Builder|AzoMerchantAccess byMerchant($merchant_id)
 * @method static Builder|AzoMerchantAccess byStore($store_id)
 * @method static Builder|AzoMerchantAccess byUserId($user_id)
 * @method static Builder|AzoMerchantAccess filterRequest(Request $request)
 * @method static Builder|AzoMerchantAccess newModelQuery()
 * @method static Builder|AzoMerchantAccess newQuery()
 * @method static Builder|AzoMerchantAccess orderRequest(Request $request, string $default_order_str = 'id:desc')
 * @method static Builder|AzoMerchantAccess query()
 * @mixin Eloquent
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

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function scopeFilterRequest(Builder $query, Request $request)
    {
        if ($q = $request->query('q')) {
            $query->where('user_name', 'LIKE', '%' . $q . '%')
                ->orWhere('phone', 'LIKE', '%' . $q . '%');
        }

        if ($request->query('date')) {
            $date = Carbon::parse($request->query('date'));
            $query->whereDate('created_at', $date);
        }

        if ($merchant = $request->query('merchant_id')) {
            $query->where('merchant_id', $merchant);
        }

        if ($store = $request->query('store_id')) {
            $query->where('store_id', $store);
        }

        if ($user = $request->query('user_id')) {
            $query->where('user_id', $user);
        }

        if ($user_ids = $request->query('user_ids')) {
            $user_ids = explode(';', $user_ids);
            $query->whereIn('user_id', $user_ids);
        }
    }

    public function scopeByMerchant(Builder $query, $merchant_id)
    {
        $query->where('merchant_id', $merchant_id);
    }

    public function scopeByActiveMerchant(Builder $query)
    {
        $query->whereHas('merchant', function ($query) {
            $query->where('active', true);
        });
    }

    public function scopeByActiveStore(Builder $query)
    {
        $query->whereHas('store', function ($query) {
            $query->where('active', true);
        });
    }

    public function scopeByStore(Builder $query, $store_id)
    {
        $query->where('store_id', $store_id);
    }

    public function scopeByUserId(Builder $query, $user_id)
    {
        $query->where('user_id', $user_id);
    }
}
