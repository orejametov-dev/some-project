<?php

namespace App\Modules\Merchants\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competitor extends Model
{
    use HasFactory;

    public function merchants()
    {
        return $this->belongsToMany(Merchant::class, 'merchant_competitor')->withPivot('volume_sales', 'percentage_approve', 'partnership_at')->withTimestamps();
    }
}
