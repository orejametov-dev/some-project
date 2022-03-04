<?php

namespace App\Modules\Merchants\Models;

use App\Filters\Merchant\MerchantFilters;
use App\HttpRepositories\HttpResponses\Prm\CompanyHttpResponse;
use App\Modules\Merchants\Traits\MerchantFileTrait;
use App\Modules\Merchants\Traits\MerchantRelationshipsTrait;
use App\Traits\SortableByQueryParams;
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
 * @property string|null $information
 * @property string|null $token
 * @property string $alifshop_slug
 * @property string|null $telegram_chat_id
 * @property int $has_general_goods
 * @property string|null $logo_url
 * @property bool $recommend
 * @property string|null $paymo_terminal
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
 * @method static Builder|Merchant filterRequest(Request $request, array $filters = [])
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
        'alifshop_slug',
        'information',
        'logo_url',
        'telegram_chat_id',
        'has_general_goods',
        'paymo_terminal_id',
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
        'alifshop_slug' => 'Алифшоп слаг',
        'information' => 'Информация',
    ];

    public function getLogoPathAttribute()
    {
        if (!$this->logo_url) {
            return null;
        }

        return config('local_services.services_storage.domain') . $this->logo_url;
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
        $merchant->alifshop_slug = Str::slug($company->name);
        $merchant->maintainer_id = $user_id;
        $merchant->company_id = $company->id;

        return $merchant;
    }

    public function scopeFilterRequest(Builder $builder, Request $request, array $filters = []): Builder
    {
        return (new MerchantFilters($request, $builder))->execute($filters);
    }
}
