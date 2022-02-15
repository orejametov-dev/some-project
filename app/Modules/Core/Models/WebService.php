<?php

namespace App\Modules\Core\Models;

use App\Traits\CacheModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Modules\Core\Models\WebService.
 *
 * @property int $id
 * @property string $name
 * @property string $token
 * @property string|null $note
 * @property int|null $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|WebService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebService query()
 * @mixin \Eloquent
 */
class WebService extends Model
{
    use HasFactory;
    use CacheModel;

    protected $hidden = [
        'token', 'note', 'created_at', 'updated_at',
    ];
}
