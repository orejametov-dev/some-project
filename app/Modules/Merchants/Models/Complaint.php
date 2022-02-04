<?php

namespace App\Modules\Merchants\Models;

use App\Traits\SortableByQueryParams;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @property int $id
 * @property int $azo_merchant_access_id
 * @property int $client_id
 * @property string $reason_correction
 * @property $created_at
 * @method static Builder|Complaint filterRequest(Request $request)
 * @method static Builder|Complaint orderRequest(Request $request, string $default_order_str = 'id:desc')
 * @method static Builder|Complaint query()
 */
class Complaint extends Model
{
    use HasFactory;
    use SortableByQueryParams;

    protected $fillable = [
        'reason_correction'
    ];

    public function azo_merchant_access()
    {
        return $this->belongsTo(AzoMerchantAccess::class, 'azo_merchant_access_id');
    }

    public function scopeFilterRequest(Builder $query, Request $request)
    {
        if ($id = $request->query('id')) {
            $query->where('id', $id);
        }
    }
}
