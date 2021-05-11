<?php

namespace App\Modules\Merchants\Models;


use App\Modules\Merchants\Services\RequestStatus;
use App\Modules\Merchants\Traits\MerchantRequestStatusesTrait;
use App\Traits\SortableByQueryParams;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;
    use MerchantRequestStatusesTrait;
    use SortableByQueryParams;

    public const TABLE_NAME = 'requests';
    protected $table = 'merchant_requests';
    protected $appends = ['status'];
    protected $fillable = [
        'name',
        'legal_name',
        'info',
        'user_name',
        'user_phone',
        'information',
        'region'
    ];

    public function getStatusAttribute()
    {
        return RequestStatus::getOneById($this->status_id);
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
}
