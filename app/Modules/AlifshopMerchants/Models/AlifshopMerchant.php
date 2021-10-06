<?php

namespace App\Modules\AlifshopMerchants\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * @method static Builder|AlifshopMerchant filterRequest(Request $request)
 * @method static Builder|AlifshopMerchant orderRequest(Request $request, string $default_order_str = 'id:desc')
 * @method static Builder|AlifshopMerchant query()
 */
class AlifshopMerchant extends Model
{
    use HasFactory;

    protected $fillable = [
        'active'
    ];

    public function scopeFilterRequest(Builder $query, Request $request)
    {
        if ($alifshop_merchant_id = $request->query('id')) {
            $query->where('id', $alifshop_merchant_id);
        }

        if ($request->query('date')) {
            $date = Carbon::parse($request->query('date'));
            $query->whereDate('created_at', $date);
        }

        if ($maintainer_id = $request->query('maintainer_id')) {
            $query->where('maintainer_id', $maintainer_id);
        }

        if($request->has('active')) {
            $query->where('active', $request->query('active'));
        }

    }
}
