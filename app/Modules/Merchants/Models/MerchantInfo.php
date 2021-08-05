<?php

namespace App\Modules\Merchants\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class MerchantInfo
 *
 * @package App\Modules\Partners\Models
 * @property int $id
 * @property string $legal_name
 * @property string $director_name
 * @property string $phone
 * @property string $vat_number
 * @property string $mfo
 * @property string $tin
 * @property string $oked
 * @property string $address
 * @property string $bank_account
 * @property string $bank_name
 * @property int $contract_number
 * @property int $merchant_id
 * @property int|null $limit
 * @property string|null $limit_expired_at
 * @property int|null $rest_limit
 * @property-read Merchant $merchant
 * @method static Builder|MerchantInfo filterRequest(Request $request)
 * @method static Builder|MerchantInfo newModelQuery()
 * @method static Builder|MerchantInfo newQuery()
 * @method static Builder|MerchantInfo query()
 * @mixin Eloquent
 */
class MerchantInfo extends Model
{
    use HasFactory;

    public const LIMIT = 100000000000;

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

    public static function getMaxContractNumber()
    {
        return MerchantInfo::max('contract_number');
    }
}
