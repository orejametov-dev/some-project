<?php

namespace App\Modules\Merchants\Models;

use App\Traits\SortableByQueryParams;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * App\Modules\Merchants\Models\Comment.
 *
 * @property string $body
 * @property string $commentable_type
 * @property int $commentable_id
 * @property int $created_by_id
 * @property string $created_by_name
 * @method static Builder|Comment filterRequest(Request $request)
 * @method static Builder|Comment orderRequest(Request $request, string $default_order_str = 'id:desc')
 * @method static Builder|Comment query()
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $commentable
 * @method static Builder|Comment newModelQuery()
 * @method static Builder|Comment newQuery()
 * @mixin Eloquent
 */
class Comment extends Model
{
    const PROBLEM_CASE_FOR_PRM = 'problem_case_for_prm';
    const PROBLEM_CASE_FOR_MERCHANT = 'problem_case_for_merchant';

    use HasFactory;
    use SortableByQueryParams;

    protected $table = 'comments';

    protected $fillable = [
        'body',
        'commentable_type',
        'created_by_name',
    ];

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
}
