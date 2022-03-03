<?php

namespace App\Modules\Merchants\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Modules\Merchants\Models\ConditionTemplate.
 *
 * @method static Builder|ConditionTemplate query()
 * @property int $id
 * @property int $duration
 * @property int $commission
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ConditionTemplate newModelQuery()
 * @method static Builder|ConditionTemplate newQuery()
 * @mixin Eloquent
 */
class ConditionTemplate extends Model
{
    use HasFactory;

    protected $table = 'application_condition_templates';
    protected $fillable = [
        'duration',
        'commission',
    ];
}
