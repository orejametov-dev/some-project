<?php

namespace App\Modules\Companies\Models;

use App\Modules\AlifshopMerchants\Models\AlifshopMerchantAccess;
use App\Modules\Merchants\Models\AzoMerchantAccess;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string|null $full_name
 * @property string|null $phone
 * @property Company $company
 * @method static Builder|CompanyUser filterRequest(Request $request)
 */
class CompanyUser extends Model
{
    use HasFactory;

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function azo_merchant_access()
    {
        return $this->hasOne(AzoMerchantAccess::class);
    }

    public function alifshop_merchant_access()
    {
        return $this->hasOne(AlifshopMerchantAccess::class);
    }

    public function scopeFilterRequest(Builder $query, Request $request)
    {
        if ($q = $request->query('q')) {
            $query->where('full_name', 'LIKE', '%' . $q . '%')
                ->orWhere('phone', 'LIKE', '%' . $q . '%');
        }

        if ($request->query('date')) {
            $date = Carbon::parse($request->query('date') ?? today());
            $query->whereDate('created_at', $date);
        }

        if ($company = $request->query('company_id')) {
            $query->where('company_id', $company);
        }

        if ($user = $request->query('user_id')) {
            $query->where('user_id', $user);
        }

        if ($user_ids = $request->query('user_ids')) {
            $user_ids = explode(';', $user_ids);
            $query->whereIn('user_id', $user_ids);
        }
    }

    public function scopeByUser(Builder $query, $user_id)
    {
        $query->where('user_id', $user_id);
    }
}
