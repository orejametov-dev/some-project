<?php

namespace App\Modules\Merchants\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * App\Modules\Merchants\Models\ProblemCaseTag.
 *
 * @property int $id
 * @property string $body
 * @property int $type_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|ProblemCase[] $problem_cases
 * @property-read int|null $problem_cases_count
 * @method static Builder|ProblemCaseTag filterRequests(Request $request)
 * @method static Builder|ProblemCaseTag newModelQuery()
 * @method static Builder|ProblemCaseTag newQuery()
 * @method static Builder|ProblemCaseTag query()
 */
class ProblemCaseTag extends Model
{
    use HasFactory;

    protected $fillable = ['body', 'type_id'];

    public const BEFORE_TYPE = 1;
    public const AFTER_TYPE = 2;

    public function problem_cases()
    {
        return $this->belongsToMany(ProblemCase::class, 'problem_cases', 'problem_case_tag_id', 'problem_case_id');
    }

    public function scopeFilterRequests(Builder $query, Request $request)
    {
        if ($request->query('q')) {
            $query->where('body', 'LIKE', '%' . $request->query('q') . '%');
        }

        if ($request->query('type_id')) {
            $query->where('type_id', $request->query('type_id'));
        }
    }
}
