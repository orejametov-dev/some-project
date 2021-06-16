<?php

namespace App\Modules\Merchants\Models;


use App\HttpServices\Storage\StorageMicroService;
use App\Modules\Merchants\Services\RequestStatus;
use App\Modules\Merchants\Traits\MerchantRequestStatusesTrait;
use App\Traits\SortableByQueryParams;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;

/**
 * App\Modules\Merchants\Models\Request
 *
 * @property int $id
 * @property string $name
 * @property string $information
 * @property string|null $legal_name
 * @property string $user_name
 * @property string $user_phone
 * @property int $status_id
 * @property string|null $region
 * @property int|null $engaged_by_id
 * @property string|null $engaged_by_name
 * @property string|null $engaged_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $status_updated_at
 * @property-read mixed $status
 * @method static Builder|Request allowed()
 * @method static Builder|Request filterRequest(\Illuminate\Http\Request $request)
 * @method static Builder|Request inProcess()
 * @method static Builder|Request new()
 * @method static Builder|Request newModelQuery()
 * @method static Builder|Request newQuery()
 * @method static Builder|Request orderRequest(\Illuminate\Http\Request $request, string $default_order_str = 'id:desc')
 * @method static Builder|Request query()
 * @method static Builder|Request trash()
 * @mixin Eloquent
 */
class Request extends Model
{
    use HasFactory;
    use MerchantRequestStatusesTrait;
    use SortableByQueryParams;

    protected $table = 'merchant_requests';
    protected $appends = ['status', 'checkers'];
    protected $casts = ['categories' => 'array'];
    protected $fillable = [
        'name',
        'user_name',
        'user_phone',
        'information',
        'region',
        'stores_count',
        'merchant_users_count',
        'address',
        'approximate_sales',
        'categories',
        'legal_name',

        'director_name',
        'legal_name',
        'phone',
        'vat_number',
        'mfo',
        'tin',
        'oked',
        'bank_account',
        'bank_name',
        'address'
    ];



    public function getCheckersAttribute()
    {
        $main = $this->user_name && $this->user_phone && $this->name && $this->region
            && $this->categories && $this->stores_count && $this->merchant_users_count && $this->approximate_sales;

        $documents = $this->director_name && $this->legal_name && $this->phone && $this->vat_number && $this->mfo
            && $this->tin && $this->oked && $this->bank_account && $this->bank_name && $this->address;

        $files = $this->files()->count() >= 7;

        return [
            'main' => $main,
            'documents' => $documents,
            'files' => $files
        ];
    }



    public function getStatusAttribute()
    {
        return RequestStatus::getOneById($this->status_id);
    }

    public function files()
    {
        return $this->hasMany(File::class, 'request_id', 'id');
    }

    public function scopeFilterRequest(Builder $query, \Illuminate\Http\Request $request)
    {
        if ($q = $request->query('q')) {
            $query->where('name', 'like', '%' . $q . '%')
                ->orWhere('information', 'like', '%' . $q . '%')
                ->orWhere('legal_name', 'like', '%' . $q . '%')
                ->orWhere('user_name', 'like', '%' . $q . '%')
                ->orWhere('user_phone', 'like', '%' . $q . '%');
        }

        if ($status = $request->query('status_id')) {
            $query->where('status_id', $status);
        }
    }

    public function uploadFile(UploadedFile $uploadedFile, $type)
    {
        $storage_file = StorageMicroService::uploadFile($uploadedFile, 'merchants');
        $merchant_request_file = new File();
        $merchant_request_file->file_type = $type;
        $merchant_request_file->mime_type = $storage_file['mime_type'];
        $merchant_request_file->size = $storage_file['size'];
        $merchant_request_file->url = $storage_file['url'];
        $merchant_request_file->request_id = $this->id;
        $merchant_request_file->save();
        return $merchant_request_file;
    }
}
