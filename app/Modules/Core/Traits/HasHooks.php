<?php

namespace App\Modules\Core\Traits;

use App\Modules\Core\Models\ModelHook;

trait HasHooks
{
    public function hooks()
    {
        return $this->morphMany(ModelHook::class, 'hookable');
    }
}
