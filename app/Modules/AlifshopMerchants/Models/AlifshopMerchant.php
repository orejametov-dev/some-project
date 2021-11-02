<?php

namespace App\Modules\AlifshopMerchants\Models;

use App\Modules\AlifshopMerchants\Traits\AlifshopMerchantRelationshipsTrait;
use App\Modules\Companies\Models\Company;
use App\Modules\Merchants\Traits\MerchantFileTrait;
use App\Modules\Merchants\Traits\MerchantStatusesTrait;
use App\Traits\SortableByQueryParams;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @property int $id
 * @property string $name
 * @property string|null $legal_name
 * @property string|null $token
 * @property string $alifshop_slug
 * @property string|null $information
 * @property int|null $maintainer_id
 * @property int $company_id
 * @property-read Company $company
 * @method static Builder|AlifshopMerchant filterRequest(Request $request)
 * @method static Builder|AlifshopMerchant orderRequest(Request $request, string $default_order_str = 'id:desc')
 * @method static Builder|AlifshopMerchant query()
 */
class AlifshopMerchant extends Model
{
    use HasFactory;
    use AlifshopMerchantRelationshipsTrait, MerchantFileTrait, SortableByQueryParams;
    use MerchantStatusesTrait;

    protected $fillable = [
        'name',
        'legal_name',
        'token',
        'alifshop_slug',
        'information',
        'logo_url',
        'active'
    ];

    protected $appends = ['logo_path'];
    protected $hidden = ['logo_url'];

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
        if ($alifshop_merchant_ids = $request->query('merchant_ids')) {
            $alifshop_merchant_ids = explode(';', $alifshop_merchant_ids);
            $query->whereIn('id', $alifshop_merchant_ids);
        }

        if ($q = $request->query('q')) {
            $query->where('name', 'like', '%' . $q . '%')
                ->orWhere('legal_name', 'like', '%' . $q . '%');
        }

        if ($alifshop_merchant_id = $request->query('id')) {
            $query->where('id', $alifshop_merchant_id);
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

        if($status_id = $request->query('status_id')) {
            $query->where('status_id', $status_id);
        }

        if ($request->has('active')) {
            $query->where('active', $request->query('active'));
        }

    }

    public function scopeActive(Builder $query)
    {
        $query->where('active', true);
    }
}
