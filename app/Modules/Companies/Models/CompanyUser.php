<?php

namespace App\Modules\Companies\Models;

use App\Modules\Merchants\Models\AzoMerchantAccess;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyUser extends Model
{
    use HasFactory;

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function azo_merchant_access()
    {
        return $this->hasOne(AzoMerchantAccess::class);
    }
}
