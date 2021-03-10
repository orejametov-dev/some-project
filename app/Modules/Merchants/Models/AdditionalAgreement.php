<?php

namespace App\Modules\Merchants\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

/**
 * Class MerchantAdditionalAgreement
 * @package App\Modules\Partners\Models
 * @property int $id
 * @property int $merchant_id
 * @property int $limit
 * @property Carbon $registration_date
 * @property int $number
 * @property Carbon $limit_expired_at
 */
class AdditionalAgreement extends Model
{
    use HasFactory;
    protected $fillable = [
        'limit',
        'registration_date',
        'number'
    ];

    /**
     * @return BelongsTo
     */
    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function scopeFilterRequest(Builder $query, Request $request)
    {
        if($request->query('merchant_id')){
            $query->where('merchant_id', $request->query('merchant_id'));
        }
    }
}
