<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProblemCaseTagTypeEnum;
use App\Filters\ProblemCaseTag\ProblemCaseTagFilters;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;

/**
 * App\Models\ProblemCaseTag.
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

    protected $casts = [
        'type_id' => ProblemCaseTagTypeEnum::class,
    ];

    public function problem_cases(): BelongsToMany
    {
        return $this->belongsToMany(ProblemCase::class, 'problem_cases', 'problem_case_tag_id', 'problem_case_id');
    }

    public function scopeFilterRequest(Builder $builder, Request $request, array $filters = []): Builder
    {
        return (new ProblemCaseTagFilters($request, $builder))->execute($filters);
    }
}
