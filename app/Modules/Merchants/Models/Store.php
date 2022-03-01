<?php

namespace App\Modules\Merchants\Models;

use App\Filters\Store\StoreFilters;
use App\Modules\Merchants\QueryBuilders\StoreQueryBuilder;
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
 * Class Store.
 *
 * @property int $id
 * @property string $name
 * @property bool $is_main
 * @property string $phone
 * @property string $address
 * @property string $region
 * @property float $lat
 * @property float $long
 * @property string $responsible_person
 * @property string $responsible_person_phone
 * @property int $merchant_id
 * @property string $client_type_register
 * @property Merchant $merchant
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Condition[] $application_conditions
 * @property-read int|null $application_conditions_count
 * @method static StoreQueryBuilder query()
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
        'client_type_register',
    ];

    /**
     * @param  \Illuminate\Database\Query\Builder $query
     * @return StoreQueryBuilder
     */
    public function newEloquentBuilder($query)
    {
        return new StoreQueryBuilder($query);
    }

    public function scopeFilterRequests(Builder $query, Request $request)
    {
        $searchIndex = $request->q;
        if ($merchant_id = $request->query('merchant_id')) {
            $query->where('merchant_id', $merchant_id);
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

        if ($request->query('region')) {
            $query->where('region', $request->query('region'));
        }

        if ($request->has('active')) {
            $query->where('active', $request->query('active'));
        }
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'store_notification', 'store_id', 'notification_id');
    }

}
