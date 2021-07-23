<?php

namespace App\Modules\Merchants\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityReason extends Model
{
    use HasFactory;

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_activities');
    }

    public function merchants()
    {
        return $this->belongsToMany(Merchant::class, 'merchant_activities');
    }
}
