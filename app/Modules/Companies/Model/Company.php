<?php

namespace App\Modules\Companies\Model;

use App\Modules\Merchants\Models\Merchant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'legal_name'];

    public function merchant()
    {
        return $this->hasOne(Merchant::class);
    }

//    public function
}
