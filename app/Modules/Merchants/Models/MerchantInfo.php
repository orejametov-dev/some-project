<?php

namespace App\Modules\Merchants\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class MerchantInfo
 * @package App\Modules\Partners\Models
 * @property string legal_name
 * @property string director_name
 * @property string phone
 * @property string vat_number
 * @property string mfo
 * @property string tin
 * @property string oked
 * @property string address
 * @property string bank_account
 * @property string bank_name
 * @property integer contract_number
 * @property integer limit
 * @property Carbon $limit_expared_at
 * @property Carbon $contract_date
 */
class MerchantInfo extends Model
{
    use HasFactory;
    protected $table = 'merchant_infos';
    protected $fillable = [
        'legal_name',
        'director_name',
        'phone',
        'vat_number',
        'mfo',
        'tin',
        'oked',
        'address',
        'bank_account',
        'bank_name',
        'contract_number',
        'limit',
        'contract_date',
    ];

    public $timestamps = false;

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function scopeFilterRequest(Builder $query, Request $request)
    {
        if ($request->query('merchant_id')) {
            $query->where('merchant_id', $request->query('merchant_id'));
        }
    }
}
