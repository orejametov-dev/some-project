<?php

namespace App\Modules\Companies\Models;

use App\Traits\CacheModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
    use CacheModel;

    public const AZO_MERCHANT = 1;
    public const ALIFSHOP_MERCHANT = 2;

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_modules');
    }
}
