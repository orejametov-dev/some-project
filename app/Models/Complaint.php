<?php

declare(strict_types=1);

namespace App\Models;

use App\Filters\Complaint\ComplaintFilters;
use App\Traits\SortableByQueryParams;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

/**
 * App\Models\Complaint.
 *
 * @property int $id
 * @property int $azo_merchant_access_id
 * @property array $meta
 * @property Carbon $created_at
 * @method static Builder|Complaint filterRequest(Request $request, array $filters = [])
 * @method static Builder|Complaint orderRequest(Request $request, string $default_order_str = 'id:desc')
 * @method static Builder|Complaint query()
 * @property Carbon|null $updated_at
 * @property-read AzoMerchantAccess $azo_merchant_access
 * @method static Builder|Complaint newModelQuery()
 * @method static Builder|Complaint newQuery()
 */
class Complaint extends Model
{
    use HasFactory;
    use SortableByQueryParams;

    protected $casts = [
        'meta' => 'json',
    ];

    public function azo_merchant_access(): BelongsTo
    {
        return $this->belongsTo(AzoMerchantAccess::class, 'azo_merchant_access_id');
    }

    public function scopeFilterRequest(Builder $builder, Request $request, array $filters = []): Builder
    {
        return (new ComplaintFilters($request, $builder))->execute($filters);
    }
}
