<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\CancelReason.
 *
 * @property int $id
 * @property string $body
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|MerchantRequest[] $merchant_requests
 * @property-read int|null $merchant_requests_count
 * @method static Builder|CancelReason newModelQuery()
 * @method static Builder|CancelReason newQuery()
 * @method static Builder|CancelReason query()
 */
class CancelReason extends Model
{
    use HasFactory;

    public function merchant_requests(): HasMany
    {
        return $this->hasMany(MerchantRequest::class);
    }
}
