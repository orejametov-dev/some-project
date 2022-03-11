<?php

declare(strict_types=1);

namespace App\Modules\Merchants\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Modules\Merchants\Models\CancelReason.
 *
 * @property int $id
 * @property string $body
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Request[] $merchant_requests
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
        return $this->hasMany(Request::class);
    }
}
