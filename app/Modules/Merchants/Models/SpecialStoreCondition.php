<?php

namespace App\Modules\Merchants\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Modules\Merchants\Models\SpecialStoreCondition.
 *
 * @property int $id
 * @property int $store_id
 * @property int $condition_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|SpecialStoreCondition newModelQuery()
 * @method static Builder|SpecialStoreCondition newQuery()
 * @method static Builder|SpecialStoreCondition query()
 */
class SpecialStoreCondition extends Model
{
    use HasFactory;
}
