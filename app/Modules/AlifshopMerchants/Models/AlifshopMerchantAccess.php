<?php

namespace App\Modules\AlifshopMerchants\Models;

use App\Modules\Companies\Models\CompanyUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

/**
 * @property int $store_id
 * @property int $user_id
 * @property string $user_name
 * @property string $phone
 * @method static Builder|AlifshopMerchantAccess byUserId($user_id)
 * @property-read AlifshopMerchant $alifshopMerchant
 * @property-read AlifshopMerchantStores $alifshopMerchantStores
 * @method static Builder|AlifshopMerchantAccess filterRequest(Request $request)
 * @method static Builder|AlifshopMerchantAccess orderRequest(Request $request, string $default_order_str = 'id:desc')
 * @method static Builder|AlifshopMerchantAccess query()
 */
class AlifshopMerchantAccess extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'user_name',
        'phone'
    ];

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
            $query->where('user_name', 'LIKE', '%' . $q . '%')
                ->orWhere('phone', 'LIKE', '%' . $q . '%');
        }

        if ($request->query('date')) {
            $date = Carbon::parse($request->query('date') ?? today());
            $query->whereDate('created_at', $date);
        }

        if ($merchant = $request->query('merchant_id')) {
            $query->where('alifshop_merchant_id', $merchant);
        }

        if ($store = $request->query('store_id')) {
            $query->where('alifshop_store_id', $store);
        }

        if ($user = $request->query('user_id')) {
            $query->where('user_id', $user);
        }

        if ($user_ids = $request->query('user_ids')) {
            $user_ids = explode(';', $user_ids);
            $query->whereIn('user_id', $user_ids);
        }
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

    public function scopeByUserId(Builder $query, $user_id)
    {
        $query->where('user_id', $user_id);
    }
}
