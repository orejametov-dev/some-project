<?php

namespace App\Modules\Merchants\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CancelReason extends Model
{
    use HasFactory;

    public function merchant_requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }
}
