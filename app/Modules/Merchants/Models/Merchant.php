<?php

namespace App\Modules\Merchants\Models;

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

/**
 * App\Modules\Merchants\Models\Merchant
 *
 * @property int $id
 * @property string $name
 * @property string|null $legal_name
 * @property string|null $information
 * @property string|null $token
 * @property string $alifshop_slug
 * @property string|null $telegram_chat_id
 * @property int $has_deliveries
 * @property int $has_manager
 * @property int $has_applications
 * @property int $has_orders
 * @property string|null $logo_url
 * @property string|null $paymo_terminal
 * @property int|null $maintainer_id
 * @property int|null $current_sales
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
 * @property-read Collection|MerchantUser[] $merchant_users
 * @property-read int|null $merchant_users_count
 * @property-read Collection|Store[] $stores
 * @property-read int|null $stores_count
 * @property-read Collection|Tag[] $tags
 * @property-read int|null $tags_count
 * @method static Builder|Merchant filterRequest(Request $request)
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
        'token',
        'alifshop_slug',
        'information',
        'logo_url',

        'telegram_chat_id',
        'has_deliveries',
        'has_manager',
        'has_applications',
        'has_orders',

        'paymo_terminal_id'
    ];
    protected $appends = ['logo_path'];
    protected $hidden = ['logo_url'];
    public static $percentage_of_limit = "* 0.95";
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


    public function scopeFilterRequest(Builder $query, Request $request)
    {
        if ($merchant_ids = $request->query('merchant_ids')) {
            $merchant_ids = explode(';', $merchant_ids);
            $query->whereIn('id', $merchant_ids);
        }

        if ($q = $request->query('q')) {
            $query->where('name', 'like', '%' . $q . '%');
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

        if ($alifshop_items = $request->query('alifshop_items')) {
            $query->where('alifshop_items', $alifshop_items);
        }

        if ($telegram_chat_id = $request->query('telegram_chat_id')) {
            $query->where('telegram_chat_id', $telegram_chat_id);
        }

        if ($has_manager = $request->query('has_manager')) {
            $query->where('has_deliveries', $has_manager);
        }

        if ($has_applications = $request->query('has_applications')) {
            $query->where('has_deliveries', $has_applications);
        }

        if ($has_orders = $request->query('has_orders')) {
            $query->where('has_orders', $has_orders);
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
    }
}
