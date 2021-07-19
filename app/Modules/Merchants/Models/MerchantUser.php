<?php

namespace App\Modules\Merchants\Models;


use App\Modules\Merchants\Services\MerchantStatus;
use App\Traits\SortableByQueryParams;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * App\Modules\Merchants\Models\MerchantUser
 *
 * @property int $id
 * @property int $merchant_id
 * @property int $store_id
 * @property int $user_id
 * @property string $user_name
 * @property string $phone
 * @property int $permission_applications
 * @property int $permission_deliveries
 * @property int $permission_orders
 * @property int $permission_manager
 * @property int $permission_upload_goods
 * @property int $permission_oso
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Merchant $merchant
 * @property-read Store $store
 * @method static Builder|MerchantUser byMerchant($merchant_id)
 * @method static Builder|MerchantUser byStore($store_id)
 * @method static Builder|MerchantUser byUserId($user_id)
 * @method static Builder|MerchantUser filterRequest(Request $request)
 * @method static Builder|MerchantUser newModelQuery()
 * @method static Builder|MerchantUser newQuery()
 * @method static Builder|MerchantUser orderRequest(Request $request, string $default_order_str = 'id:desc')
 * @method static Builder|MerchantUser query()
 * @mixin Eloquent
 */
class MerchantUser extends Model
{
    use HasFactory;
    use SortableByQueryParams;

    protected $table = 'merchant_users';
    protected $fillable = [
        'permission_manager',
        'permission_orders',
        'permission_deliveries',
        'permission_applications',
        'permission_upload_goods',
        'permission_oso',
        'user_name',
        'phone'
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
            $date = Carbon::parse($request->query('date') ?? today());
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

        if ($permission_applications = $request->query('permission_applications')) {
            $query->where('permission_applications', $permission_applications);
        }
        if ($permission_orders = $request->query('permission_orders')) {
            $query->where('permission_orders', $permission_orders);
        }
        if ($permission_deliveries = $request->query('permission_deliveries')) {
            $query->where('permission_deliveries', $permission_deliveries);
        }
        if ($permission_applications = $request->query('permission_applications')) {
            $query->where('permission_applications', $permission_applications);
        }
        if ($permission_upload_goods = $request->query('permission_upload_goods')) {
            $query->where('permission_upload_goods', $permission_upload_goods);
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

    public function scopeByStore(Builder $query, $store_id)
    {
        $query->where('store_id', $store_id);
    }

    public function scopeByUserId(Builder $query, $user_id)
    {
        $query->where('user_id', $user_id);
    }
}
