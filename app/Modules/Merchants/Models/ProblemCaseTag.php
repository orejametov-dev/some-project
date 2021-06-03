<?php

namespace App\Modules\Merchants\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
