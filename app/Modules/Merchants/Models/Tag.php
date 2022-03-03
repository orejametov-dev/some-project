<?php

namespace App\Modules\Merchants\Models;

use App\Filters\Tag\TagFilters;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
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
 * @mixin Eloquent
 * @method static Builder|Tag filterRequest(Request $request, array $filters = [])
 * @method static Builder|Tag filterRequests(Request $request)
 * @method static Builder|Tag newModelQuery()
 * @method static Builder|Tag newQuery()
 */
class Tag extends Model
{
    use HasFactory;

    protected $table = 'merchant_tags';

    public function merchants()
    {
        return $this->morphedByMany(Merchant::class, 'merchant', 'merchant_tag', 'tag_id', 'merchant_id')->withTimestamps();
    }

    public function scopeFilterRequests(Builder $query, Request $request)
    {
        if ($request->query('q')) {
            $query->where('title', 'LIKE', '%' . $request->query('q') . '%');
        }
    }

    public function scopeFilterRequest(Builder $builder, Request $request, array $filters = [])
    {
        return (new TagFilters($request, $builder))->execute($filters);
    }
}
