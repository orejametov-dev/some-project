<?php

namespace App\Modules\Merchants\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $table = 'merchant_tags';

    public function merchants()
    {
        return $this->belongsToMany(Merchant::class, 'merchant_tag', 'tag_id', 'merchant_id')->withTimestamps();
    }
}
