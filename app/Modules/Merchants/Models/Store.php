<?php

namespace App\Modules\Merchants\Models;

use App\Filters\Store\StoreFilters;
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
 * @method static Builder|Store filterRequest(Request $request, array $filters = [])
 * @method static Builder|Store main()
 * @method static Builder|Store newModelQuery()
 * @method static Builder|Store newQuery()
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
        'client_type_register',
    ];

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

    public function scopeActive(Builder $query)
    {
        $query->where('active', true);
    }

    public function scopeFilterRequest(Builder $builder, Request $request, array $filters = []): Builder
    {
        return (new StoreFilters($request, $builder))->execute($filters);
    }
}
