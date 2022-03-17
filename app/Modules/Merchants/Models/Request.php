<?php

declare(strict_types=1);

namespace App\Modules\Merchants\Models;

use App\Filters\MerchantRequest\MerchantRequestFilters;
use App\HttpRepositories\HttpResponses\Auth\AuthHttpResponse;
use App\HttpRepositories\Storage\StorageHttpRepository;
use App\Modules\Merchants\Traits\MerchantRequestStatusesTrait;
use App\Services\SimpleStateMachine\SimpleStateMachineTrait;
use App\Traits\SortableByQueryParams;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * App\Modules\Merchants\Models\Request.
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
 * @method static Builder|Request allowed()
 * @method static Builder|Request filterRequest(\Illuminate\Http\Request $request, array $filters = [])
 * @method static Builder|Request inProcess()
 * @method static Builder|Request onTraining()
 * @method static Builder|Request new()
 * @method static Builder|Request newModelQuery()
 * @method static Builder|Request newQuery()
 * @method static Builder|Request orderRequest(\Illuminate\Http\Request $request, string $default_order_str = 'id:desc')
 * @method static Builder|Request query()
 * @method static Builder|Request trash()
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
 * @method static Builder|Request onlyByToken($token)
 */
class Request extends Model
{
    use HasFactory;
    use MerchantRequestStatusesTrait;
    use SimpleStateMachineTrait;
    use SortableByQueryParams;

    public const NEW = 1;
    public const ALLOWED = 2;
    public const TRASH = 3;
    public const IN_PROCESS = 4;
    public const ON_TRAINING = 5;

    protected $table = 'merchant_requests';
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

    private static array $statuses = [
        self::NEW => [
            'id' => self::NEW,
            'name' => 'новый',
        ],
        self::ALLOWED => [
            'id' => self::ALLOWED,
            'name' => 'Одобрено',
        ],
        self::TRASH => [
            'id' => self::TRASH,
            'name' => 'В корзине',
        ],
        self::IN_PROCESS => [
            'id' => self::IN_PROCESS,
            'name' => 'На переговорах',
        ],
        self::ON_TRAINING => [
            'id' => self::ON_TRAINING,
            'name' => 'На обучении',
        ],
    ];

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

    public static function getOneById(int $id): mixed
    {
        return json_decode(json_encode(self::$statuses[$id]));
    }

    public function getStateAttribute(): ?int
    {
        return $this->status_id;
    }

    public function getStatusAttribute(): object
    {
        return self::getOneById($this->status_id);
    }

    public function getSimpleStateMachineMap(): array
    {
        return [
            self::NEW => [
                self::IN_PROCESS,
            ],
            self::IN_PROCESS => [
                self::ON_TRAINING,
                self::TRASH,
            ],
            self::ON_TRAINING => [
                self::ALLOWED,
            ],
            self::ALLOWED => [],
            self::TRASH => [],
        ];
    }

    public static function statusLists(): array
    {
        return [
            ['id' => self::NEW, 'name' => 'Новый'],
            ['id' => self::IN_PROCESS, 'name' => 'На переговорах'],
            ['id' => self::ON_TRAINING, 'name' => 'На обучении'],
            ['id' => self::ALLOWED, 'name' => 'Одобрено'],
            ['id' => self::TRASH, 'name' => 'В корзине'],
        ];
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'request_id', 'id');
    }

    public function cancel_reason(): BelongsTo
    {
        return $this->belongsTo(CancelReason::class);
    }

    public function scopeOnlyByToken(Builder $query, string $token): Builder
    {
        return $query->where('token', $token);
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

    public function scopeFilterRequest(Builder $builder, \Illuminate\Http\Request $request, array $filters = []): Builder
    {
        return (new MerchantRequestFilters($request, $builder))->execute($filters);
    }
}
