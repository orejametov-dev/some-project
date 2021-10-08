<?php

namespace App\Modules\AlifshopMerchants\Models;

use App\Modules\Companies\Models\CompanyUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SortableByQueryParams;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

/**
 * @property int $id
 * @property int $store_id
 * @method static Builder|AlifshopMerchantAccess byUserId($user_id)
 * @property-read AlifshopMerchant $alifshop_merchant
 * @property-read AlifshopMerchantStores $alifshop_merchant_store
 * @method static Builder|AlifshopMerchantAccess filterRequest(Request $request)
 * @method static Builder|AlifshopMerchantAccess orderRequest(Request $request, string $default_order_str = 'id:desc')
 * @method static Builder|AlifshopMerchantAccess query()
 */
class AlifshopMerchantAccess extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SortableByQueryParams;

    protected $fillable = [];

    public function alifshop_merchant()
    {
        return $this->belongsTo(AlifshopMerchant::class);
    }

    public function alifshop_merchant_store()
    {
        return $this->belongsTo(AlifshopMerchantStores::class);
    }

    public function company_user()
    {
        return $this->belongsTo(CompanyUser::class);
    }

    public function scopeFilterRequest(Builder $query, Request $request)
    {
        if ($q = $request->query('q')) {
            $query->whereHas('company_user' , function ($query) use ($q) {
                $query->where('user_name', 'LIKE', '%' . $q . '%')
                    ->orWhere('phone', 'LIKE', '%' . $q . '%');
            });
        }

        if ($request->query('date')) {
            $date = Carbon::parse($request->query('date') ?? today());
            $query->whereDate('created_at', $date);
        }

        if ($alifshop_merchant = $request->query('merchant_id')) {
            $query->where('alifshop_merchant_id', $alifshop_merchant);
        }

        if ($alifshop_merchant_store = $request->query('store_id')) {
            $query->where('alifshop_merchant_store_id', $alifshop_merchant_store);
        }

        if ($user = $request->query('user_id')) {
            $query->whereHas( 'company_users' , function ($query) use ($user) {
                $query->where('user_id', $user);
        });
        }

        if ($user_ids = $request->query('user_ids')) {
            $user_ids = explode(';', $user_ids);
            $query->whereHas( 'company_user' , function ($query)  use ($user_ids){
                $query->whereIn('user_id', $user_ids);
            });
        }
    }

    public function scopeByActiveMerchant(Builder $query)
    {
        $query->whereHas('alifshop_merchant', function ($query) {
            $query->where('active', true);
        });
    }

    public function scopeByActiveStore(Builder $query)
    {
        $query->whereHas('alifshop_merchant_store', function ($query) {
            $query->where('active', true);
        });
    }

    public function scopeByUserId(Builder $query, $user_id)
    {
        $query->whereHas('company_user' , function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
        });
    }
}
