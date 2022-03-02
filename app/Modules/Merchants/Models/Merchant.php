<?php

namespace App\Modules\Merchants\Models;

use App\HttpRepositories\HttpResponses\Prm\CompanyHttpResponse;
use App\Modules\Merchants\QueryBuilders\MerchantQueryBuilder;
use App\Modules\Merchants\Traits\MerchantFileTrait;
use App\Traits\SortableByQueryParams;
use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
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
 * @method static MerchantQueryBuilder query()
 * @mixin Eloquent
 */
class Merchant extends Model
{
    use HasFactory;

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

    /**
     * @param Builder $query
     * @return MerchantQueryBuilder
     */
    public function newEloquentBuilder($query)
    {
        return new MerchantQueryBuilder($query);
    }

    public function getLogoPathAttribute()
    {
        if (!$this->logo_url) {
            return null;
        }

        return config('local_services.services_storage.domain') . $this->logo_url;
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

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }

    public function azo_merchant_accesses(): HasMany
    {
        return $this->hasMany(AzoMerchantAccess::class);
    }

    public function application_conditions(): HasMany
    {
        return $this->hasMany(Condition::class);
    }

    public function application_active_conditions(): HasMany
    {
        return $this->hasMany(Condition::class)->where('active', true);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'merchant', 'merchant_tag', 'merchant_id', 'tag_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'merchant_id', 'id');
    }

    public function merchant_info(): HasOne
    {
        return $this->hasOne(MerchantInfo::class);
    }

    public function additional_agreements(): HasMany
    {
        return $this->hasMany(AdditionalAgreement::class);
    }

    public function activity_reasons(): BelongsToMany
    {
        return $this->belongsToMany(ActivityReason::class, 'merchant_activities', 'merchant_id', 'activity_reason_id')->withTimestamps()
            ->withPivot(['id', 'merchant_id', 'activity_reason_id', 'active', 'created_by_id', 'created_by_name', 'created_at', 'updated_at']);
    }

    public function competitors(): BelongsToMany
    {
        return $this->belongsToMany(Competitor::class, 'merchant_competitor')->withPivot('volume_sales', 'percentage_approve', 'partnership_at')->withTimestamps();
    }
}
