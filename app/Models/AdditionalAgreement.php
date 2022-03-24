<?php

declare(strict_types=1);

namespace App\Models;

use App\Filters\AdditionalAgreement\AdditionalAgreementFilters;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

/**
 * Class MerchantAdditionalAgreement.
 *
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
 * @method static Builder|AdditionalAgreement filterRequest(Request $request, array $filters = []))
 * @method static Builder|AdditionalAgreement newModelQuery()
 * @method static Builder|AdditionalAgreement newQuery()
 * @method static Builder|AdditionalAgreement query()
 * @property string|null $document_type
 */
class AdditionalAgreement extends Model
{
    use HasFactory;

    const LIMIT = 'limit';
    const VAT = 'vat';
    const DELIVERY = 'delivery';

    protected $table = 'merchant_additional_agreements';
    protected $fillable = [
        'limit',
        'registration_date',
        'number',
        'document_type',
    ];

    /**
     * @return BelongsTo
     */
    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function scopeFilterRequest(Builder $builder, Request $request, array $filters = []): Builder
    {
        return (new AdditionalAgreementFilters($request, $builder))->execute($filters);
    }
}
