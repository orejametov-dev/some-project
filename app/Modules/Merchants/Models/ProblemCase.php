<?php

namespace App\Modules\Merchants\Models;

use App\Filters\ProblemCase\ProblemCaseFilters;
use App\Modules\Merchants\Traits\ProblemCaseStatuses;
use App\Services\SimpleStateMachine\SimpleStateMachinable;
use App\Services\SimpleStateMachine\SimpleStateMachineTrait;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;

/**
 * App\Modules\Merchants\Models\ProblemCase.
 *
 * @property int $id
 * @property int $merchant_id
 * @property int $store_id
 * @property int $status_id
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
 * @property string $status_updated_at
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
 * @method static Builder|ProblemCase done()
 * @method static Builder|ProblemCase filterRequest(Request $request, array $filters = [])
 * @method static Builder|ProblemCase filterRequests(Request $request)
 * @method static Builder|ProblemCase finished()
 * @method static Builder|ProblemCase inProcess()
 * @method static Builder|ProblemCase new()
 * @method static Builder|ProblemCase newModelQuery()
 * @method static Builder|ProblemCase newQuery()
 * @method static Builder|ProblemCase onlyNew()
 * @method static Builder|ProblemCase query()
 * @mixin Eloquent
 */
class ProblemCase extends Model implements SimpleStateMachinable
{
    use HasFactory;
    use ProblemCaseStatuses;
    use SimpleStateMachineTrait;

    public const NEW = 1;
    public const IN_PROCESS = 2;
    public const DONE = 3;
    public const FINISHED = 4;

    public static $sources = ['CALLS', 'LAW', 'COMPLIANCE'];

    public static $statuses = [
        self::NEW => [
            'id' => self::NEW,
            'name' => 'Новый',
            'lang' => [
                'uz' => 'Yangi',
                'ru' => 'Новый',
            ],
        ],
        self::IN_PROCESS => [
            'id' => self::IN_PROCESS,
            'name' => 'В процессе',
            'lang' => [
                'uz' => 'Ko\'rib chiqilmoqda',
                'ru' => 'В процессе',
            ],
        ],
        self::DONE => [
            'id' => self::DONE,
            'name' => 'Выполнено',
            'lang' => [
                'uz' => 'Bajarildi',
                'ru' => 'Выполнено',
            ],
        ],
        self::FINISHED => [
            'id' => self::FINISHED,
            'name' => 'Завершен',
            'lang' => [
                'uz' => 'Tugatildi',
                'ru' => 'Завершен',
            ],
        ],
    ];

    public static function getOneById(int $id)
    {
        return json_decode(json_encode(self::$statuses[$id]));
    }

    public function getStateAttribute()
    {
        return $this->status_id;
    }

    public function getSimpleStateMachineMap(): array
    {
        return [
            self::NEW => [
                self::IN_PROCESS,
            ],
            self::IN_PROCESS => [
                self::DONE,
            ],
            self::DONE => [
                self::IN_PROCESS,
                self::FINISHED,
            ],
            self::FINISHED => [],
        ];
    }

    protected $fillable = [
        'status_id',
        'status_key',
        'created_by_id',
        'created_by_name',
        'created_from_name',
        'credit_number',
        'application_id',
        'client_id',
        'application_items',
        'application_created_at',
        'credit_contract_date',
        'post_or_pre_created_by_id',
        'post_or_pre_created_by_name',
    ];

    protected $casts = [
        'application_items' => 'array',
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
            ->where('type_id', ProblemCaseTag::BEFORE_TYPE);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function scopeFilterRequests(Builder $query, Request $request)
    {
        if ($client = $request->query('q')) {
            collect(explode(' ', $client))->filter()->each(function ($q) use ($query) {
                $q = '%' . $q . '%';

                $query->where(function ($query) use ($q) {
                    $query->where('client_name', 'like', $q)
                        ->orWhere('client_surname', 'like', $q)
                        ->orWhere('client_patronymic', 'like', $q)
                        ->orWhere('phone', 'like', $q);
                });
            });
        }

        if ($id = $request->query('id')) {
            $query->where('id', $id);
        }

        if ($request->merchant_id) {
            $query->where('merchant_id', $request->merchant_id);
        }

        if ($request->store_id) {
            $query->where('store_id', $request->store_id);
        }

        if ($request->query('engaged_by_id')) {
            $query->where('engaged_by_id', $request->query('engaged_by_id'));
        }

        if ($request->query('created_at')) {
            $query->where('created_at', $request->query('created_at'));
        }

        if ($request->query('client_id')) {
            $query->where('client_id', $request->query('client_id'));
        }

        if ($request->query('assigned_to_id')) {
            $query->where('assigned_to_id', $request->query('assigned_to_id'));
        }

        if ($request->query('date')) {
            $date = Carbon::parse($request->query('date'));
            $query->whereDate('created_at', $date);
        }

        if ($request->query('tag_id')) {
            $query->whereHas('tags', function ($query) use ($request) {
                $query->where('problem_case_tag_id', $request->query('tag_id'));
            });
        }

        if ($request->query('source')) {
            $query->where('created_from_name', 'LIKE', '%' . $request->query('source') . '%');
        }

        if ($request->query('status_id')) {
            $query->where('status_id', $request->query('status_id'));
        }
    }

    public function scopeOnlyNew(Builder $query): Builder
    {
        return $query->where('status_id', self::NEW);
    }

    public function scopeByMerchant(Builder $query, $merchant_id): Builder
    {
        return $query->where('merchant_id', $merchant_id);
    }

    public function scopeByStore(Builder $query, $store_id): Builder
    {
        return $query->where('store_id', $store_id);
    }

    public function scopeFilterRequest(Builder $builder, Request $request, array $filters = []): Builder
    {
        return (new ProblemCaseFilters($request, $builder))->execute($filters);
    }
}
