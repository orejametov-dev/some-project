<?php

namespace App\Modules\Merchants\Models;


use App\Traits\SortableByQueryParams;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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
        'permission_oso'
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

    public function scopeByStore(Builder $query, $store_id)
    {
        $query->where('store_id', $store_id);
    }

    public function scopeByUserId(Builder $query, $user_id)
    {
        $query->where('user_id', $user_id);
    }
}
