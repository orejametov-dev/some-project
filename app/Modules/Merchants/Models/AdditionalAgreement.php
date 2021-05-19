<?php

namespace App\Modules\Merchants\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

/**
 * Class MerchantAdditionalAgreement
 *
 * @package App\Modules\Partners\Models
 * @property int $id
 * @property int $merchant_id
 * @property int $limit
 * @property Carbon $registration_date
 * @property int $number
 * @property Carbon $limit_expired_at
 * @property int|null $rest_limit
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Merchant $merchant
 * @method static Builder|AdditionalAgreement filterRequest(Request $request)
 * @method static Builder|AdditionalAgreement newModelQuery()
 * @method static Builder|AdditionalAgreement newQuery()
 * @method static Builder|AdditionalAgreement query()
 * @mixin Eloquent
 */
class AdditionalAgreement extends Model
{
    use HasFactory;

    protected $table = 'merchant_additional_agreements';
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
        if ($request->query('merchant_id')) {
            $query->where('merchant_id', $request->query('merchant_id'));
        }
    }
}
