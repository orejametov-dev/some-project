<?php

namespace App\Modules\Merchants\Models;

use App\Filters\ProblemCaseTag\ProblemCaseTagFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;

/**
 * @method static Builder|ProblemCase filterRequest(Request $request, array $filters = [])
 * @method static Builder|AzoMerchantAccess query()
 */
class ProblemCaseTag extends Model
{
    use HasFactory;

    protected $fillable = ['body', 'type_id'];

    public const BEFORE_TYPE = 1;
    public const AFTER_TYPE = 2;

    public function problem_cases(): BelongsToMany
    {
        return $this->belongsToMany(ProblemCase::class, 'problem_cases', 'problem_case_tag_id', 'problem_case_id');
    }

    public function scopeFilterRequest(Builder $builder, Request $request, array $filters = []): Builder
    {
        return (new ProblemCaseTagFilters($request, $builder))->execute($filters);
    }
}
