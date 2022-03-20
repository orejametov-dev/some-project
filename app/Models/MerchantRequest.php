<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MerchantRequestStatusEnum;
use App\Filters\MerchantRequest\MerchantRequestFilters;
use App\Mappings\MerchantRequestStatusMapping;
use App\Traits\SortableByQueryParams;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * App\Models\MerchantRequest.
 *
 * @property int $id
 * @property string $name
 * @property string|null $legal_name
 * @property string|null $legal_name_prefix
 * @property string $user_name
 * @property string $user_phone
 * @property string $address
 * @property int $status_id
 * @property bool $main_completed
 * @property bool $documents_completed
 * @property bool $file_completed
 * @property string|null $region
 * @property string|null $district
 * @property int|null $engaged_by_id
 * @property string|null $engaged_by_name
 * @property array $categories
 * @property int $approximate_sales
 * @property Carbon|null $engaged_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $status_updated_at
 * @property string $created_from_name
 * @property-read Collection|File[] $files
 * @property-read mixed $status
 * @method static Builder|MerchantRequest filterRequest(Request $request, array $filters = [])
 * @method static Builder|MerchantRequest new()
 * @method static Builder|MerchantRequest newModelQuery()
 * @method static Builder|MerchantRequest newQuery()
 * @method static Builder|MerchantRequest orderRequest(Request $request, string $default_order_str = 'id:desc')
 * @method static Builder|MerchantRequest query()
 * @property int|null $stores_count
 * @property int|null $merchant_users_count
 * @property string|null $director_name
 * @property string|null $phone
 * @property string|null $vat_number
 * @property string|null $mfo
 * @property string|null $tin
 * @property string|null $oked
 * @property string|null $bank_account
 * @property string|null $bank_name
 * @property int $completed
 * @property int|null $cancel_reason_id
 * @property-read CancelReason|null $cancel_reason
 * @property-read int|null $files_count
 * @property-read mixed $state
 */
class MerchantRequest extends Model
{
    use HasFactory;
    use SortableByQueryParams;

    protected $appends = ['status'];

    protected $dates = [
        'status_updated_at',
        'engaged_at',
    ];

    protected $casts = ['categories' => 'array'];

    private function getStatusMachineMapping(): array
    {
        return [
            MerchantRequestStatusEnum::NEW()->getValue() => [
                MerchantRequestStatusEnum::IN_PROCESS(),
            ],
            MerchantRequestStatusEnum::IN_PROCESS()->getValue() => [
                MerchantRequestStatusEnum::ON_TRAINING(),
                MerchantRequestStatusEnum::TRASH(),
            ],
            MerchantRequestStatusEnum::ON_TRAINING()->getValue() => [
                MerchantRequestStatusEnum::ALLOWED(),
            ],
            MerchantRequestStatusEnum::ALLOWED()->getValue() => [],
            MerchantRequestStatusEnum::TRASH()->getValue() => [],
        ];
    }

    public function getStatusAttribute(): array
    {
        $mapping = new MerchantRequestStatusMapping();

        return $mapping->getMappedValue(MerchantRequestStatusEnum::from($this->status_id));
    }

    public function isStatusNew(): bool
    {
        return $this->status_id === MerchantRequestStatusEnum::NEW()->getValue();
    }

    public function isInProcess(): bool
    {
        return $this->status_id === MerchantRequestStatusEnum::IN_PROCESS()->getValue();
    }

    public function isOnTraining(): bool
    {
        return $this->status_id === MerchantRequestStatusEnum::ON_TRAINING()->getValue();
    }

    public function scopeNew(Builder $builder): Builder
    {
        return $builder->where('status_id', MerchantRequestStatusEnum::NEW());
    }

    public function setStatus(MerchantRequestStatusEnum $statusEnum)
    {
        $this->assertStatusSwitch($statusEnum);

        $this->status_updated_at = Carbon::now();
        $this->status_id = $statusEnum->getValue();
    }

    public function assertStatusSwitch(MerchantRequestStatusEnum $statusEnum): void
    {
        if ($this->status_id !== null and array_key_exists($this->status_id, $this->getStatusMachineMapping()) === false) {
            throw new InvalidArgumentException('Initial status does not mapped');
        }

        if ($this->status_id !== null and in_array($statusEnum, $this->getStatusMachineMapping()[$this->status_id]) === false) {
            throw new InvalidArgumentException('Assigned status does not mapped');
        }
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'request_id', 'id');
    }

    public function cancel_reason(): BelongsTo
    {
        return $this->belongsTo(CancelReason::class);
    }

    public function scopeFilterRequest(Builder $builder, Request $request, array $filters = []): Builder
    {
        return (new MerchantRequestFilters($request, $builder))->execute($filters);
    }

    public function checkToMainCompleted(): void
    {
        $main = $this->user_name && $this->legal_name && $this->legal_name_prefix && $this->user_phone && $this->name && $this->region
            && $this->categories && $this->approximate_sales;

        if ($main === true) {
            $this->main_completed = true;
            $this->save();
        }
    }
}
