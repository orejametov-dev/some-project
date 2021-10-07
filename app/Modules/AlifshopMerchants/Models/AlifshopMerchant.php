<?php

namespace App\Modules\AlifshopMerchants\Models;

use App\Modules\AlifshopMerchants\Traits\AlifshopMerchantRelationshipsTrait;
use App\Modules\Companies\Models\Company;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
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
    use AlifshopMerchantRelationshipsTrait;

    protected $fillable = [
        'name',
        'legal_name',
        'token',
        'alifshop_slug',
        'information',
        'logo_url',
        'active'
    ];

    protected $hidden = ['logo_url'];

    public static $attributeLabels = [
        'name' => 'Название партнёра',
        'legal_name' => 'Юридическое имя',
        'token' => 'Токен алифшопа',
        'alifshop_slug' => 'Алифшоп слаг',
        'information' => 'Информация',
    ];

    public function scopeFilterRequest(Builder $query, Request $request)
    {
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

        if($request->has('active')) {
            $query->where('active', $request->query('active'));
        }

    }
}
