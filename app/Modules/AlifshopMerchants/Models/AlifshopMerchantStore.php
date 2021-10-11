<?php

namespace App\Modules\AlifshopMerchants\Models;

use App\Modules\AlifshopMerchants\Traits\AlifshopMerchantStoreRelationshipsTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Traits\SortableByQueryParams;

/**
 * @property $id
 * @property $name
 * @property $is_main
 * @property $phone
 * @property $address
 * @property $region
 * @property $lat
 * @property $long
 * @property bool $actives
 * @property $alifshop_merchant_id
 * @property AlifshopMerchant $alifshop_merchant
 * @method static Builder|AlifshopMerchantStore main()
 * @method static Builder|AlifshopMerchantStore filterRequest(Request $request)
 * @method static Builder|AlifshopMerchantStore query()
 */
class AlifshopMerchantStore extends Model
{
    use HasFactory;
    use AlifshopMerchantStoreRelationshipsTrait, SortableByQueryParams;

    protected $table = 'alifshop_merchant_stores';
    protected $fillable = [
        'name',
        'is_main',
        'phone',
        'address',
        'region',
        'lat',
        'long',
        'active',

    ];

    public function scopeMain($query)
    {
        return $query->where('is_main', true);
    }

    public function scopeFilterRequest(Builder $query, Request $request)
    {
        $searchIndex = $request->q;
        if ($alifshop_merchant_id = $request->query('merchant_id')) {
            $query->where('alifshop_merchant_id', $alifshop_merchant_id);
        }

        if ($alifshop_merchant_id = $request->query('alifshop_merchant_id')) {
            $query->where('alifshop_merchant_id', $alifshop_merchant_id);
        }

        if ($alifshop_merchant_store_ids = $request->query('store_ids')) {
            $store_ids = explode(';', $alifshop_merchant_store_ids);
            $query->whereIn('id', $store_ids);
        }

        if ($alifshop_merchant_store_ids = $request->query('alifshop_store_ids')) {
            $store_ids = explode(';', $alifshop_merchant_store_ids);
            $query->whereIn('id', $store_ids);
        }

        if ($is_main = $request->query('is_main')) {
            $query->where('is_main', $is_main);
        }

        if ($searchIndex) {
            $query->where('name', 'like', '%' . $searchIndex . '%');
        }

        if($request->query('region')) {
            $query->where('region', $request->query('region'));
        }
    }
}
