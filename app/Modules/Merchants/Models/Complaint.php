<?php

namespace App\Modules\Merchants\Models;

use App\Traits\SortableByQueryParams;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string|null $surname
 * @property string|null $patronymic
 * @property string $reason_correction
 * @property $created_at
 * @method static Builder|Complaint filterRequest(Request $request)
 * @method static Builder|Complaint orderRequest(Request $request, string $default_order_str = 'id:desc')
 * @method static Builder|Complaint query()
 */
class Complaint extends Model
{
    use HasFactory;
    use SortableByQueryParams;

    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'reason_correction'
    ];

    public function scopeFilterRequest(Builder $query, Request $request)
    {
        if ($id = $request->query('id')) {
            $query->where('id', $id);
        }

        if ($user = $request->query('q')) {
            collect(explode(' ', $user))->filter()->each(function ($q) use ($query) {
                $q = '%' . $q . '%';

                $query->where(function ($query) use ($q) {
                    $query->where('name', 'like', $q)
                        ->orWhere('surname', 'like', $q)
                        ->orWhere('patronymic', 'like', $q);
                });
            });
        }
    }
}
