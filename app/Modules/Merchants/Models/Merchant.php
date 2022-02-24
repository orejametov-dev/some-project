<?php

namespace App\Modules\Merchants\Models;

use App\Filters\Merchant\MerchantFilters;
use App\HttpRepositories\HttpResponses\Prm\CompanyHttpResponse;
use App\Modules\Merchants\Traits\MerchantFileTrait;
use App\Modules\Merchants\Traits\MerchantRelationshipsTrait;
use App\Traits\SortableByQueryParams;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * App\Modules\Merchants\Models\Merchant.
 *
 * @property int $id
 * @property string $name
 * @property string|null $legal_name
 * @property string|null $legal_name_prefix
 * @property string|null $token
 * @property int $has_general_goods
 * @property string|null $logo_url
 * @property bool $recommend
 * @property int|null $maintainer_id
 * @property int|null $current_sales
 * @property int $company_id
 * @property int|null $min_application_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection|AdditionalAgreement[] $additional_agreements
 * @property-read int|null $additional_agreements_count
 * @property-read Collection|Condition[] $application_conditions
 * @property-read int|null $application_conditions_count
 * @property-read Collection|File[] $files
 * @property-read int|null $files_count
 * @property-read mixed $logo_path
 * @property-read MerchantInfo|null $merchant_info
 * @property-read Collection|AzoMerchantAccess[] $azo_merchant_accesses
 * @property-read int|null $merchant_users_count
 * @property-read Collection|Store[] $stores
 * @property-read int|null $stores_count
 * @property-read Collection|Tag[] $tags
 * @property-read int|null $tags_count
 * @method static Builder|Merchant filterRequests(Request $request)
 * @method static Builder|Merchant newModelQuery()
 * @method static Builder|Merchant newQuery()
 * @method static Builder|Merchant orderRequest(Request $request, string $default_order_str = 'id:desc')
 * @method static Builder|Merchant query()
 * @mixin Eloquent
 */
class Merchant extends Model
{
    use HasFactory;

    use MerchantRelationshipsTrait;
    use MerchantFileTrait;
    use SortableByQueryParams;

    protected $table = 'merchants';
    protected $fillable = [
        'name',
        'legal_name',
        'legal_name_prefix',
        'token',
        'logo_url',
        'has_general_goods',
        'min_application_price',
        'active',
    ];
    protected $appends = ['logo_path'];
    protected $hidden = ['logo_url'];
    public static $percentage_of_limit = '* 0.95';
    /*Поля моделей используется в model_hooks*/
    public static $attributeLabels = [
        'name' => 'Название партнёра',
        'legal_name' => 'Юридическое имя',
        'token' => 'Токен алифшопа',
    ];

    public function getLogoPathAttribute()
    {
        if (!$this->logo_url) {
            return null;
        }

        return config('local_services.services_storage.domain') . $this->logo_url;
    }

    public function scopeFilterRequests(Builder $query, Request $request)
    {
        if ($merchant_ids = $request->query('merchant_ids')) {
            $merchant_ids = explode(';', $merchant_ids);
            $query->whereIn('id', $merchant_ids);
        }

        if ($q = $request->query('q')) {
            $query->where(function ($query) use ($q) {
                $query->where('legal_name', 'like', '%' . $q . '%')
                    ->orWhere('name', 'like', '%' . $q . '%');
            });

            if (is_numeric($q)) {
                $query->orWhereHas('merchant_info', function (Builder $query) use ($q) {
                    $query->Where('tin', $q)
                        ->orWhere('contract_number', $q);
                });
            }
        }

        if ($merchant_id = $request->query('merchant_id')) {
            $query->where('id', $merchant_id);
        }

        if ($merchant_id = $request->query('id')) {
            $query->where('id', $merchant_id);
        }

        if ($legal_name = $request->query('legal_name')) {
            $query->where('legal_name', $legal_name);
        }

        if ($request->query('date')) {
            $date = Carbon::parse($request->query('date'));
            $query->whereDate('created_at', $date);
        }

        if ($maintainer_id = $request->query('maintainer_id')) {
            $query->where('maintainer_id', $maintainer_id);
        }

        if ($tags_string = $request->query('tags')) {
            $tags = explode(';', $tags_string);

            $query->whereHas('tags', function ($query) use ($tags) {
                $query->whereIn('id', $tags);
            });
        }

        if ($region = $request->query('region')) {
            $query->whereHas('stores', function ($query) use ($region) {
                $query->where('region', $region);
            });
        }

        if ($token = $request->query('token')) {
            $query->where('token', $token);
        }

        if ($status_id = $request->query('status_id')) {
            $query->where('status_id', $status_id);
        }

        if ($request->has('active')) {
            $query->where('active', $request->query('active'));
        }

        if ($request->query('tin')) {
            $query->whereHas('merchant_info', function ($query) use ($request) {
                $query->where('tin', $request->query('tin'));
            });
        }
    }

    public function scopeActive(Builder $query)
    {
        $query->where('active', true);
    }

    public static function fromDto(CompanyHttpResponse $company, int $user_id)
    {
        $merchant = new self();
        $merchant->id = $company->id;
        $merchant->name = $company->name;
        $merchant->legal_name = $company->legal_name;
        $merchant->legal_name_prefix = $company->legal_name_prefix;
        $merchant->token = $company->token;
        $merchant->maintainer_id = $user_id;
        $merchant->company_id = $company->id;

        return $merchant;
    }

    public function scopeFilterRequest(Builder $builder, Request $request, array $filters = []): Builder
    {
        return (new MerchantFilters($request, $builder))->execute($filters);
    }
}
