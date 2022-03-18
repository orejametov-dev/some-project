<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MerchantRequestStatusEnum;
use App\Filters\MerchantRequest\MerchantRequestFilters;
use App\HttpRepositories\Storage\StorageHttpRepository;
use App\Mappings\MerchantRequestStatusMapping;
use App\Traits\SortableByQueryParams;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
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
    protected $fillable = [
        'name',
        'user_name',
        'user_phone',
        'region',
        'district',
        'stores_count',
        'merchant_users_count',
        'address',
        'approximate_sales',
        'categories',
        'legal_name',
        'legal_name_prefix',

        'director_name',
        'legal_name',
        'phone',
        'vat_number',
        'mfo',
        'tin',
        'oked',
        'bank_account',
        'bank_name',
        'address',

        'main_completed',
        'documents_completed',
    ];

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
        if (array_key_exists($this->status_id, $this->getStatusMachineMapping()) === false) {
            throw new InvalidArgumentException('Initial status does not mapped');
        }

        if (in_array($statusEnum, $this->getStatusMachineMapping()[$this->status_id]) === false) {
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

    public function checkToDocumentsCompleted(): void
    {
        $documents = $this->director_name && $this->phone && $this->vat_number && $this->mfo
            && $this->tin && $this->oked && $this->bank_account && $this->bank_name && $this->address;

        if ($documents === true) {
            $this->documents_completed = true;
            $this->save();
        }
    }

    public function checkToFileCompleted(): void
    {
        $exist_file_type = $this->files->pluck('file_type')->toArray();
        $file_checker = true;
        unset(File::$registration_file_types['store_photo']);
        foreach (File::$registration_file_types as $key => $file_type) {
//            $file_checker = $file_checker && true;
            if (in_array($key, $exist_file_type) === false) {
                $file_checker = false;
            }
        }

        if ($file_checker === true) {
            $this->file_completed = true;
            $this->save();
        }
    }

    public function uploadFile(UploadedFile $uploadedFile, string $type): File
    {
        $storage_file = (new StorageHttpRepository)->uploadFile($uploadedFile, 'merchants');
        $merchant_request_file = new File();
        $merchant_request_file->file_type = $type;
        $merchant_request_file->mime_type = $storage_file->getMimeType();
        $merchant_request_file->size = $storage_file->getSize();
        $merchant_request_file->url = $storage_file->getUrl();
        $merchant_request_file->request_id = $this->id;
        $merchant_request_file->save();

        return $merchant_request_file;
    }

    public function deleteFile(int $file_id): void
    {
        $file = $this->files()->find($file_id);
        if (!$file) {
            return;
        }

        (new StorageHttpRepository)->destroy($file->url);
        $file->delete();
    }
}
