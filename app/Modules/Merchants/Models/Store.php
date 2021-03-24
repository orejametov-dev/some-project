<?php

namespace App\Modules\Merchants\Models;


use App\Modules\Merchants\Traits\StoreRelationshipsTrait;
use App\Traits\SortableByQueryParams;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class Store
 * @package App\Modules\Partners\Models
 *
 * @property $id
 * @property $name
 * @property $is_main
 * @property $phone
 * @property $address
 * @property $region
 * @property $lat
 * @property $long
 * @property $responsible_person
 * @property $responsible_person_phone
 * @property $merchant_id
 * @property Merchant $merchant
 */

class Store extends Model
{
    use HasFactory;
    use StoreRelationshipsTrait;
    use SortableByQueryParams;

    protected $table = 'stores';
    protected $fillable = ['name', 'is_main', 'phone', 'address', 'region', 'lat', 'long', 'responsible_person', 'responsible_person_phone'];

    public function scopeFilterRequest(Builder $query, Request $request)
    {
        $searchIndex = $request->q;
        if ($merchant_id = $request->query('merchant_id')) {
            $query->where('merchant_id', $merchant_id);
        }

        if ($store_id = $request->query('store_id')) {
            $query->where('store_id', $store_id);
        }

        if ($store_ids = $request->query('store_ids')) {
            $store_ids = explode(';', $store_ids);
            $query->whereIn('id', $store_ids);
        }

        if ($is_main = $request->query('is_main')) {
            $query->where('is_main', $is_main);
        }

        if ($searchIndex) {
            $query->where('name', 'like', '%' . $searchIndex . '%');
        }
    }

    public function scopeMain($query)
    {
        return $query->where('is_main', true);
    }
}
