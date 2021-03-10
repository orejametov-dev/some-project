<?php

namespace App\Modules\Core\Models;

use App\Services\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelHook extends Model
{
    use HasFactory;
    protected $fillable = ['body', 'class', 'action', 'keyword'];

    public static function make($model, $body, $keyword, $action, $class = null)
    {
        $modelHook = new static;
        $modelHook->body = $body;
        $modelHook->keyword = $keyword;
        $modelHook->action = $action;
        $modelHook->class = $class;
        $modelHook->hookable_type = $model->getTable();
        $modelHook->hookable_id = $model->id;
        $modelHook->created_from()->associate(app(WebService::class));
        $modelHook->created_by_id = optional(app(User::class))->id;
        $modelHook->save();

        return $modelHook;
    }

    public function created_from()
    {
        return $this->belongsTo(WebService::class);
    }

    public function hookable()
    {
        return $this->morphTo();
    }
}
