<?php

namespace App\Modules\Companies\Models;

use App\Modules\AlifshopMerchants\Models\AlifshopMerchant;
use App\Modules\Merchants\Models\Merchant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @property int $id
 * @property string $name
 * @property string|null $legal_name
 * @method static Builder|Merchant filterRequest(Request $request)

 */
class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'legal_name'];

    public function company_users()
    {
        return $this->hasMany(CompanyUser::class);
    }

    public function merchant()
    {
        return $this->hasOne(Merchant::class);
    }
    public function alifshop_merchant()
    {
        return $this->hasOne(AlifshopMerchant::class);
    }

    public function scopeFilterRequest(Builder $query, Request $request)
    {
        if ($q = $request->query('q')) {
            $query->where('name', 'like', '%' . $q . '%')
                ->orWhere('legal_name', 'like', '%' . $q . '%');

        }

        if ($request->query('date')) {
            $date = Carbon::parse($request->query('date'));
            $query->whereDate('created_at', $date);
        }
    }
}
