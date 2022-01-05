<?php

namespace App\Modules\Merchants\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static Builder|ConditionTemplate query()
 */

class ConditionTemplate extends Model
{
    use HasFactory;

    protected $table = 'application_condition_templates';
    protected $fillable = [
        'duration',
        'commission'
    ];
}
