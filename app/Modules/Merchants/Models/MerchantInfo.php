<?php

declare(strict_types=1);

namespace App\Modules\Merchants\Models;

use App\DTOs\MerchantInfos\StoreMerchantInfoDTO;
use App\Filters\MerchantInfo\MerchantInfoFilters;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

/**
 * Class MerchantInfo.
 *
 * @property int $id
 * @property string $legal_name
 * @property string $legal_name_prefix
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
 * @property Carbon|null $contract_date
 * @property int|null $rest_limit
 * @property-read Merchant $merchant
 * @method static Builder|MerchantInfo filterRequest(Request $request, array $filters = [])
 * @method static Builder|MerchantInfo newModelQuery()
 * @method static Builder|MerchantInfo newQuery()
 * @method static Builder|MerchantInfo query()
 */
class MerchantInfo extends Model
{
    use HasFactory;

    public const LIMIT = 100000000000;

    protected $table = 'merchant_infos';
    protected $dates = [
        'contract_date',
    ];
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
        'legal_name_prefix',
    ];

    public $timestamps = false;

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public static function getMaxContractNumber()
    {
        return self::max('contract_number');
    }

    public static function fromDTO(StoreMerchantInfoDTO $storeMerchantInfoDTO): self
    {
        $merchantInfo = new self();

        $merchantInfo->merchant_id = $storeMerchantInfoDTO->merchant_id;
        $merchantInfo->director_name = $storeMerchantInfoDTO->director_name;
        $merchantInfo->phone = $storeMerchantInfoDTO->phone;
        $merchantInfo->vat_number = $storeMerchantInfoDTO->vat_number;
        $merchantInfo->mfo = $storeMerchantInfoDTO->mfo;
        $merchantInfo->tin = $storeMerchantInfoDTO->tin;
        $merchantInfo->oked = $storeMerchantInfoDTO->oked;
        $merchantInfo->bank_account = $storeMerchantInfoDTO->bank_account;
        $merchantInfo->bank_name = $storeMerchantInfoDTO->bank_name;
        $merchantInfo->address = $storeMerchantInfoDTO->address;
        $merchantInfo->contract_number = self::getMaxContractNumber() + 1;
        $merchantInfo->contract_date = now();
        $merchantInfo->limit = self::LIMIT;

        return $merchantInfo;
    }

    public function scopeFilterRequest(Builder $builder, Request $request, array $filters = []): Builder
    {
        return (new MerchantInfoFilters($request, $builder))->execute($filters);
    }
}
