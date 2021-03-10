<?php

namespace App\Traits;

use App\Modules\Core\Models\WebService;
use App\Services\User;

/**
 * Trait HasCreatedByFrom
 * @package App\Traits\Models
 *
 * @property $created_by_id
 * @property $updated_by_id
 * @property $created_by_str
 * @property $updated_by_str
 * @property $created_from_id
 * @property $updated_from_id
 * @property User $created_by
 * @property User $updated_by
 */
trait HasCreatedByFrom
{
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_from_id = app(WebService::class)->id;
            $model->updated_from_id = app(WebService::class)->id;

            $model->created_by_id = optional(app(User::class))->id;
            $model->updated_by_id = optional(app(User::class))->id;
        });
        static::updating(function ($model) {
            $model->updated_from_id = app(WebService::class)->id;
            $model->updated_by_id = optional(app(User::class))->id;
        });
    }

    public function created_from()
    {
        return $this->belongsTo(WebService::class);
    }

    public function updated_from()
    {
        return $this->belongsTo(WebService::class);
    }
}
