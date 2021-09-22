<?php

namespace App\Modules\Companies\Models;

use App\Modules\Merchants\Models\Merchant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string|null $legal_name
 */
class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'legal_name'];

    public function company_users()
    {
        return $this->hasMany(CompanyUser::class);
    }

    public function merchant()
    {
        return $this->hasOne(Merchant::class);
    }
}
