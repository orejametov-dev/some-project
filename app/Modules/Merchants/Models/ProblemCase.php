<?php

namespace App\Modules\Merchants\Models;

use App\Modules\Merchants\Traits\ProblemCaseStatuses;
use App\Services\SimpleStateMachine\SimpleStateMachinable;
use App\Services\SimpleStateMachine\SimpleStateMachineTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
                'ru' => 'Новый'
            ]
        ],
        self::IN_PROCESS => [
            'id' => self::IN_PROCESS,
            'name' => 'В процессе',
            'lang' => [
                'uz' => 'Ko\'rib chiqilmoqda',
                'ru' => 'В процессе'
            ]
        ],
        self::DONE => [
            'id' => self::DONE,
            'name' => 'Выполнено',
            'lang' => [
                'uz' => 'Bajarildi',
                'ru' => 'Выполнено'
            ]
        ],
        self::FINISHED => [
            'id' => self::FINISHED,
            'name' => 'Завершен',
            'lang' => [
                'uz' => 'Tugatildi',
                'ru' => 'Завершен'
            ]
        ]
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
                self::IN_PROCESS
            ],
            self::IN_PROCESS => [
                self::DONE,
            ],
            self::DONE => [
                self::IN_PROCESS,
                self::FINISHED
            ],
            self::FINISHED => []
        ];
    }

    protected $fillable = [
        'status_id',
        'status_key',
        'created_by_id',
        'created_by_name',
        'created_from_name',
        'manager_comment',
        'merchant_comment',
        'credit_number',
        'application_id',
        'client_id',
        'application_items',
        'application_created_at',
        'credit_contract_date'
    ];

    protected $casts = [
        'application_items' => 'array'
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function tags()
    {
        return $this->belongsToMany(ProblemCaseTag::class, 'problem_case_tag', 'problem_case_id', 'problem_case_tag_id');
    }

    public function before_tags()
    {
        return $this->belongsToMany(ProblemCaseTag::class, 'problem_case_tag', 'problem_case_id', 'problem_case_tag_id')
            ->where('type_id', ProblemCaseTag::BEFORE_TYPE);
    }

    public function scopeFilterRequests(Builder $query, \Illuminate\Http\Request $request)
    {
        if($request->merchant_id) {
            $query->where('merchant_id', $request->merchant_id);
        }

        if($request->store_id) {
            $query->where('store_id', $request->store_id);
        }

        if($request->query('engaged_by_id')) {
            $query->where('engaged_by_id', $request->query('engaged_by_id'));
        }

        if($request->query('created_at')) {
            $query->where('created_at', $request->query('created_at'));
        }

        if($request->query('client_id')) {
            $query->where('client_id', $request->query('client_id'));
        }

        if($request->query('q')) {
            $query->where('search_index', 'LIKE', "%{$request->input('q')}%");
        }

        if($request->query('tag_id')) {
            $query->whereHas('tags', function ($query) use ($request) {
                $query->where('problem_case_tag_id', $request->query('tag_id'));
            });
        }

        if($request->query('source')){
            $query->where('created_from_name', 'LIKE', '%' . $request->query('source'). '%');
        }

        if($request->query('status_id')) {
            $query->where('status_id', $request->query('status_id'));
        }
    }

    public function scopeOnlyNew(Builder $query)
    {
        $query->where('status_id', self::NEW);
    }

    public function scopeByMerchant(Builder $query, $merchant_id)
    {
        $query->where('merchant_id', $merchant_id);
    }

    public function scopeByStore(Builder $query, $store_id)
    {
        $query->where('store_id', $store_id);
    }
}
