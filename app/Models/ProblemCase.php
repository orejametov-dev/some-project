<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProblemCaseStatusEnum;
use App\Enums\ProblemCaseTagTypeEnum;
use App\Filters\ProblemCase\ProblemCaseFilters;
use App\Mappings\ProblemCaseStatusMapping;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;
use InvalidArgumentException;

/**
 * App\Models\ProblemCase.
 *
 * @property int $id
 * @property int $merchant_id
 * @property int $store_id
 * @property ProblemCaseStatusEnum $status_id
 * @property string $status_key
 * @property int $created_by_id
 * @property string $created_by_name
 * @property string $created_from_name
 * @property string $credit_number
 * @property int $application_id
 * @property int $client_id
 * @property array $application_items
 * @property Carbon $application_created_at
 * @property Carbon $credit_contract_date
 * @property int $post_or_pre_created_by_id
 * @property string $post_or_pre_created_by_name
 * @property string $search_index
 * @property string $client_name
 * @property string $client_surname
 * @property string $client_patronymic
 * @property string $phone
 * @property string $description
 * @property string $merchant_comment
 * @property int $engaged_by_id
 * @property string $engaged_by_name
 * @property string $comment_from_merchant
 * @property Carbon $deadline
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property ProblemCaseTag|null $before_tags
 * @property ProblemCaseTag|null $tags
 * @property-read Store|null $store
 * @property Carbon $status_updated_at
 * @property int|null $assigned_to_id
 * @property string|null $assigned_to_name
 * @property string|null $manager_comment
 * @property string|null $engaged_at
 * @property-read int|null $before_tags_count
 * @property-read Collection|Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read mixed $state
 * @property-read Merchant $merchant
 * @property-read int|null $tags_count
 * @method static Builder|ProblemCase byMerchant($merchant_id)
 * @method static Builder|ProblemCase byStore($store_id)
 * @method static Builder|ProblemCase filterRequest(Request $request, array $filters = [])
 * @method static Builder|ProblemCase newModelQuery()
 * @method static Builder|ProblemCase newQuery()
 * @method static Builder|ProblemCase onlyNew()
 * @method static Builder|ProblemCase query()
 */
class ProblemCase extends Model
{
    use HasFactory;

    public static array $sources = ['CALLS', 'LAW', 'COMPLIANCE'];

    protected $dates = [
        'status_updated_at',
    ];
    protected $casts = [
        'application_items' => 'array',
        'status_id' => ProblemCaseStatusEnum::class,
    ];

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(ProblemCaseTag::class, 'problem_case_tag', 'problem_case_id', 'problem_case_tag_id');
    }

    public function before_tags(): BelongsToMany
    {
        return $this->belongsToMany(ProblemCaseTag::class, 'problem_case_tag', 'problem_case_id', 'problem_case_tag_id')
            ->where('type_id', ProblemCaseTagTypeEnum::BEFORE());
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function scopeByMerchant(Builder $query, int $merchant_id): Builder
    {
        return $query->where('merchant_id', $merchant_id);
    }

    public function scopeByStore(Builder $query, int $store_id): Builder
    {
        return $query->where('store_id', $store_id);
    }

    public function scopeFilterRequest(Builder $builder, Request $request, array $filters = []): Builder
    {
        return (new ProblemCaseFilters($request, $builder))->execute($filters);
    }

    public function scopeOnlyNew(Builder $query): Builder
    {
        return $query->where('status_id', ProblemCaseStatusEnum::NEW());
    }

    public function isStatusNew(): bool
    {
        return $this->status_id->equals(ProblemCaseStatusEnum::NEW());
    }

    public function isStatusInProcess(): bool
    {
        return $this->status_id->equals(ProblemCaseStatusEnum::IN_PROCESS());
    }

    public function isStatusDone(): bool
    {
        return $this->status_id->equals(ProblemCaseStatusEnum::DONE());
    }

    public function isStatusFinished(): bool
    {
        return $this->status_id->equals(ProblemCaseStatusEnum::FINISHED());
    }

    public function setStatus(ProblemCaseStatusEnum $statusEnum): self
    {
        $this->assertStatusSwitch($statusEnum);

        $this->status_updated_at = Carbon::now();
        $this->status_id = $statusEnum->getValue();
        $this->status_key = (new ProblemCaseStatusMapping())->getMappedValue($statusEnum)['name'];

        return $this;
    }

    private function getStatusMachineMapping(): array
    {
        return [
            ProblemCaseStatusEnum::NEW()->getValue() => [
                ProblemCaseStatusEnum::IN_PROCESS(),
            ],
            ProblemCaseStatusEnum::IN_PROCESS()->getValue() => [
                ProblemCaseStatusEnum::DONE(),
            ],
            ProblemCaseStatusEnum::DONE()->getValue() => [
                ProblemCaseStatusEnum::IN_PROCESS(),
                ProblemCaseStatusEnum::FINISHED(),
            ],
            ProblemCaseStatusEnum::FINISHED()->getValue() => [],
        ];
    }

    public function assertStatusSwitch(ProblemCaseStatusEnum $statusEnum): void
    {
        if ($this->status_id !== null and array_key_exists($this->status_id->getValue(), $this->getStatusMachineMapping()) === false) {
            throw new InvalidArgumentException('Initial status does not mapped');
        }

        if ($this->status_id !== null and in_array($statusEnum, $this->getStatusMachineMapping()[$this->status_id->getValue()]) === false) {
            throw new InvalidArgumentException('Assigned status does not mapped');
        }
    }
}
