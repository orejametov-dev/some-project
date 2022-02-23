<?php

namespace App\Modules\Merchants\Models;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\Filters\Notification\NotificationFilters;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;

/**
 * Class Notification.
 *
 * @property int $id
 * @property string $title_uz
 * @property string $title_ru
 * @property string $body_uz
 * @property string $body_ru
 * @property Carbon $start_schedule
 * @property Carbon $end_schedule
 * @property string $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Notification filterRequests(Request $request)
 * @method static Builder|Notification query()
 */
class Notification extends Model
{
    use HasFactory;

    public const ALL_TYPE = 'ALL';
    public const CERTAIN_TYPE = 'CERTAIN';

    protected $fillable = [
        'title_uz',
        'title_ru',
        'body_ru',
        'body_uz',
        'start_schedule',
        'end_schedule',
        'type',
    ];

    public function setAllType()
    {
        $this->type = self::ALL_TYPE;
    }

    public function setCertainType()
    {
        $this->type = self::CERTAIN_TYPE;
    }

    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class, 'store_notification', 'notification_id', 'store_id');
    }

    public function setCreatedBy(GatewayAuthUser $user)
    {
        $this->created_by_id = $user->getId();
        $this->created_by_name = $user->getName();
    }

    public function scopeOnlyByMerchant(Builder $query, $merchant_id)
    {
        $query->whereHas('stores', function (Builder $query) use ($merchant_id) {
            $query->where('stores.merchant_id', $merchant_id);
        });
    }

    public function scopeOnlyByStore(Builder $query, $store_id)
    {
        $query->whereHas('stores', function (Builder $query) use ($store_id) {
            $query->where('stores.id', $store_id);
        });
    }

    public function scopeFilterRequests(Builder $query, Request $request)
    {
        if ($request->query('q')) {
            $query->where('title_uz', 'LIKE', '%' . $request->query('q') . '%')
                ->orWhere('title_ru', 'LIKE', '%' . $request->query('q') . '%');
        }
        if ($request->merchant_id) {
            $query->whereHas('stores', function (Builder $query) use ($request) {
                $query->where('stores.merchant_id', $request->merchant_id);
            });
        }

        if ($request->query('created_by_id')) {
            $query->where('created_by_id', $request->query('created_by_id'));
        }

        if ($request->query('created_at')) {
            $date = \Carbon\Carbon::parse($request->query('created_at'))->format('Y-m-d');
            $query->whereDate('created_at', $date);
        }

        if ($request->has('published') && $request->query('published') == true) {
            $query->where('start_schedule', '<=', now())
                ->where('end_schedule', '>=', now());
        }

        if ($request->has('published') && $request->query('published') == false) {
            $query->where('end_schedule', '<=', now());
        }
    }

    public function scopeOnlyMoreThanStartSchedule(Builder $query)
    {
        $query->where('start_schedule', '<=', now());
    }

    public function scopeFilterRequest(Builder $builder, Request $request, array $filters = [])
    {
        return (new NotificationFilters($request, $builder))->execute($filters);
    }
}
