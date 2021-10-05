<?php

namespace App\Modules\AlifshopMerchants\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * @method static Builder|AlifshopMerchant filterRequest(Request $request)
 * @method static Builder|AlifshopMerchant orderRequest(Request $request, string $default_order_str = 'id:desc')
 */
class AlifshopMerchant extends Model
{
    use HasFactory;

    protected $fillable = [
        'active'
    ];

    public function scopeFilterRequest(Builder $query, Request $request)
    {
        if ($merchant_ids = $request->query('merchant_ids')) {
            $merchant_ids = explode(';', $merchant_ids);
            $query->whereIn('id', $merchant_ids);
        }

        if ($q = $request->query('q')) {
            $query->where('name', 'like', '%' . $q . '%')
                ->orWhere('legal_name', 'like', '%' . $q . '%');

            if(is_numeric($q)){
                $query->orWhereHas('merchant_info', function (Builder $query) use ($q) {
                    $query->Where('tin',  $q)
                        ->orWhere('contract_number', $q);
                });
            }
        }

        if ($merchant_id = $request->query('merchant_id')) {
            $query->where('id', $merchant_id);
        }

        if ($merchant_id = $request->query('id')) {
            $query->where('id', $merchant_id);
        }

        if ($legal_name = $request->query('legal_name')) {
            $query->where('legal_name', $legal_name);
        }

        if ($alifshop_items = $request->query('alifshop_items')) {
            $query->where('alifshop_items', $alifshop_items);
        }

        if ($telegram_chat_id = $request->query('telegram_chat_id')) {
            $query->where('telegram_chat_id', $telegram_chat_id);
        }

        if ($request->query('date')) {
            $date = Carbon::parse($request->query('date'));
            $query->whereDate('created_at', $date);
        }

        if ($maintainer_id = $request->query('maintainer_id')) {
            $query->where('maintainer_id', $maintainer_id);
        }

        if ($tags_string = $request->query('tags')) {
            $tags = explode(';', $tags_string);

            $query->whereHas('tags', function ($query) use ($tags) {
                $query->whereIn('id', $tags);
            });
        }

        if ($region = $request->query('region')) {
            $query->whereHas('stores', function ($query) use ($region) {
                $query->where('region', $region);
            });
        }

        if ($token = $request->query('token')) {
            $query->where('token', $token);
        }

        if($status_id = $request->query('status_id')) {
            $query->where('status_id', $status_id);
        }

        if($request->has('active')) {
            $query->where('active', $request->query('active'));
        }

        if($request->query('tin')) {
            $query->whereHas('merchant_info', function ($query) use ($request) {
                $query->where('tin', $request->query('tin'));
            });
        }
    }
}
