<?php

namespace App\Modules\Core\Models;

use App\Traits\CacheModel;
use App\Traits\SortableByQueryParams;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebService extends Model
{
    use HasFactory;
    use CacheModel;

    use SortableByQueryParams;


    protected $hidden = [
        'token', 'note', 'created_at', 'updated_at'
    ];
    protected $fillable = [
        'name',
        'token',
        'note'
    ];
}
