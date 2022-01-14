<?php

namespace App\Modules\Merchants\Models;

use App\Traits\SortableByQueryParams;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @property string $body
 * @property string $commentable_type
 * @property string $commentable_id
 * @method static Builder|Comment filterRequest(Request $request)
 * @method static Builder|Comment orderRequest(Request $request, string $default_order_str = 'id:desc')
 * @method static Builder|Comment query()
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
        'created_by_name'
    ];

    public function commentable()
    {
        return $this->morphTo();
    }
}