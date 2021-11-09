<?php

namespace App\Modules\Merchants\Models;


use App\Modules\AlifshopMerchants\Models\AlifshopMerchant;
use App\Modules\Merchants\Traits\StoreRelationshipsTrait;
use App\Traits\SortableByQueryParams;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * Class Store
 *
 * @package App\Modules\Partners\Models
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
 * @property $is_alifshop
 * @property $is_azo
 * @property $client_type_register
 * @property Merchant $merchant
 * @property AlifshopMerchant $alifshop_merchant
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Condition[] $application_conditions
 * @property-read int|null $application_conditions_count
 * @method static Builder|Store filterRequest(Request $request)
 * @method static Builder|Store main()
 * @method static Builder|Store alifshop()
 * @method static Builder|Store newModelQuery()
 * @method static Builder|Store newQuery()
 * @method static Builder|Store byAlifshopMerchant($alifshop_merchant_id)
 * @method static Builder|Store orderRequest(Request $request, string $default_order_str = 'id:desc')
 * @method static Builder|Store query()
 * @mixin Eloquent
 */
class Store extends Model
{
    use HasFactory;
    use StoreRelationshipsTrait;
    use SortableByQueryParams;

    protected $table = 'stores';
    protected $fillable = [
        'name',
        'is_main',
        'phone',
        'address',
        'region',
        'lat',
        'long',
        'responsible_person',
        'responsible_person_phone',
        'active',
        'district',
        'client_type_register'
    ];

    public function scopeFilterRequest(Builder $query, Request $request)
    {
        $searchIndex = $request->q;
        if ($merchant_id = $request->query('merchant_id')) {
            $query->where('merchant_id', $merchant_id);
        }

        if ($alifshop_merchant_id = $request->query('alifshop_merchant_id')) {
            $query->where('merchant_id', $alifshop_merchant_id);
        }

        if ($store_id = $request->query('store_id')) {
            $query->where('id', $store_id);
        }

        if ($store_id = $request->query('id')) {
            $query->where('id', $store_id);
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

        if($request->query('region')) {
            $query->where('region', $request->query('region'));
        }
    }

    public function scopeMain($query)
    {
        return $query->where('is_main', true);
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'store_notification', 'store_id', 'notification_id');
    }

    public function scopeByMerchant(Builder $query, $merchant_id)
    {
        $query->where('merchant_id', $merchant_id);
    }

    public function scopeByAlifshopMerchant(Builder $query, $alifshop_merchant_id)
    {
        $query->where('merchant_id', $alifshop_merchant_id);
    }

    public function scopeActive(Builder $query)
    {
        $query->where('active', true);
    }

    public function scopeAlifshop(Builder $query)
    {
        $query->where('is_alifshop', true);
    }

    public function scopeAzo(Builder $query)
    {
        $query->where('is_azo', true);
    }
}
