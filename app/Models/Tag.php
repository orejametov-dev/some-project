<?php

declare(strict_types=1);

namespace App\Models;

use App\Filters\Tag\TagFilters;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\Request;

/**
 * App\Models\Tag.
 *
 * @property int $id
 * @property string $title
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Merchant[] $merchants
 * @property-read int|null $merchants_count
 * @method static Builder|Tag query()
 * @method static Builder|Tag filterRequest(Request $request, array $filters = [])
 * @method static Builder|Tag newModelQuery()
 * @method static Builder|Tag newQuery()
 */
class Tag extends Model
{
    use HasFactory;

    protected $table = 'merchant_tags';

    public function merchants(): MorphToMany
    {
        return $this->morphedByMany(Merchant::class, 'merchant', 'merchant_tag', 'tag_id', 'merchant_id')->withTimestamps();
    }

    public function scopeFilterRequest(Builder $builder, Request $request, array $filters = []): Builder
    {
        return (new TagFilters($request, $builder))->execute($filters);
    }
}
