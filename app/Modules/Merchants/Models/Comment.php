<?php

namespace App\Modules\Merchants\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $table = 'comments';

    protected $fillable = [
        'body',
        'from_manager',
        'from_merchant'
    ];

    public function problem_cases()
    {
        return $this->morphToMany(ProblemCase::class, 'comment', 'problem_case_comment', 'comment_id', 'problem_case_id');
    }
}
