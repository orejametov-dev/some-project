<?php

declare(strict_types=1);

namespace App\Models;

use App\Filters\Merchant\MerchantFilters;
use App\HttpRepositories\HttpResponses\Prm\CompanyHttpResponse;
use App\Traits\SortableByQueryParams;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\Request;

/**
 * App\Models\Merchant.
 *
 * @property int $id
 * @property string $name
 * @property string|null $legal_name
 * @property string|null $legal_name_prefix
 * @property string|null $token
 * @property bool $has_general_goods
 * @property string|null $logo_url
 * @property bool $recommend
 * @property bool $holding_initial_payment
 * @property bool $integration
 * @property int|null $maintainer_id
 * @property int|null $current_sales
 * @property int $company_id
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
 * @property int $payment_day
 * @property bool $active
 * @property-read Collection|ActivityReason[] $activity_reasons
 * @property-read int|null $activity_reasons_count
 * @property-read Collection|Condition[] $application_active_conditions
 * @property-read int|null $application_active_conditions_count
 * @property-read int|null $azo_merchant_accesses_count
 * @property-read Collection|Competitor[] $competitors
 * @property-read int|null $competitors_count
 * @method static Builder|Merchant newModelQuery()
 * @method static Builder|Merchant newQuery()
 * @method static Builder|Merchant orderRequest(Request $request, string $default_order_str = 'id:desc')
 * @method static Builder|Merchant query()
 * @method static Builder|Merchant active()
 * @method static Builder|Merchant filterRequest(Request $request, array $filters = [])
 */
class Merchant extends Model
{
    use HasFactory;
    use SortableByQueryParams;

    protected $table = 'merchants';
    protected $appends = ['logo_path'];
    protected $hidden = ['logo_url'];
    public static string $percentage_of_limit = '* 0.95';

    public function getLogoPathAttribute(): string|null
    {
        if (!$this->logo_url) {
            return null;
        }

        return config('local_services.services_storage.domain') . $this->logo_url;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public static function fromDto(CompanyHttpResponse $company, int $user_id): self
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

    public function merchant_activities(): HasMany
    {
        return $this->hasMany(MerchantActivity::class, 'merchant_id');
    }

    public function scopeFilterRequest(Builder $builder, Request $request, array $filters = []): Builder
    {
        return (new MerchantFilters($request, $builder))->execute($filters);
    }
}
