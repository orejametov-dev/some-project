<?php

namespace App\Modules\Core\Models;

use App\Traits\HasCreatedByFrom;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    use HasCreatedByFrom;

    protected $fillable = ['body'];
    protected $appends = [
        'fresh'
    ];
    protected $hidden = [
        'created_from_id',
        'updated_from_id',
        'created_by_id',
        'updated_by_id',
        'created_from',
        'updated_from'
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function getFreshAttribute()
    {
        return $this->created_at >= now()->subHour();
    }

    public function scopeFilterRequest($query, $request)
    {
        if ($request->query('commentable_type')) {
            $query->where('commentable_type', $request->query('commentable_type'));
        }
        if ($request->query('commentable_id')) {
            $query->where('commentable_id', $request->query('commentable_id'));
        }
    }
}
