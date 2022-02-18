<?php

namespace App\Http\Controllers\ApiOnlineGateway\Merchants;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiOnlineGateway\MerchantResource;
use App\Http\Resources\ApiOnlineGateway\MerchantTagResource;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Tag;
use Illuminate\Http\Request;

class MerchantsController extends Controller
{
    public function index(Request $request)
    {
        $query = Merchant::query()
            ->whereHas('application_conditions', function ($query) {
                $query->where('active', true);
            })
            ->with('tags')
            ->active()
            ->filterRequests($request)
            ->orderByDesc('recommend')
            ->orderByDesc('current_sales');

        return MerchantResource::collection($query->paginate($request->query('per_page') ?? 15));
    }

    public function show($id, Request $request)
    {
        $merchant = Merchant::query()
            ->with('tags')
            ->active()
            ->filterRequests($request)
            ->findOrFail($id);

        return new MerchantResource($merchant);
    }

    public function tags(Request $request)
    {
        $query = Tag::query();

        return MerchantTagResource::collection($query->paginate($request->query('per_page') ?? 15));
    }
}
