<?php

namespace App\Modules\Merchants\Models;

use App\Modules\Merchants\QueryBuilders\StoreQueryBuilder;
use App\Modules\Merchants\Traits\StoreRelationshipsTrait;
use App\Traits\SortableByQueryParams;
use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
 * @property string[] $filable
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

    public function notifications() : BelongsToMany
    {
        return $this->belongsToMany(Notification::class, 'store_notification', 'store_id', 'notification_id');
    }
}
