<?php

namespace App\Modules\Merchants\Models;

use App\Modules\Merchants\Traits\ProblemCaseStatuses;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProblemCase extends Model
{
    use HasFactory;
    use ProblemCaseStatuses;

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
        'application_items'
    ];

    protected $casts = [
        'application_items' => 'array'
    ];

    public function merchant()
    {
        $this->belongsTo(Merchant::class);
    }

    public function store()
    {
        $this->belongsTo(Store::class);
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
        if($request->query('merchant_id')) {
            $query->where('merchant_id', $request->query('merchant_id'));
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
    }
}
