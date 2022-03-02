<?php

namespace App\Modules\Merchants\Models;

use App\Filters\Tag\TagFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;

/**
 * App\Modules\Merchants\Models\Tag.
 *
 * @property int $id
 * @property string $title
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Merchant[] $merchants
 * @property-read int|null $merchants_count
 * @method static Builder|Tag query()
 */
class Tag extends Model
{
    use HasFactory;

    protected $table = 'merchant_tags';

    public function merchants(): MorphToMany
    {
        return $this->morphedByMany(Merchant::class, 'merchant', 'merchant_tag', 'tag_id', 'merchant_id')->withTimestamps();
    }

    public function scopeFilterRequests(Builder $query, \Illuminate\Http\Request $request): Builder
    {
        if ($request->query('q')) {
            $query->where('title', 'LIKE', '%' . $request->query('q') . '%');
        }
    }

    public function scopeFilterRequest(Builder $builder, \Illuminate\Http\Request $request, array $filters = []): Builder
    {
        return (new TagFilters($request, $builder))->execute($filters);
    }
}
