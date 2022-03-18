<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiOnlineGateway\Merchants;

use App\Filters\CommonFilters\TagsFilter;
use App\Filters\Merchant\QMerchantFilter;
use App\Filters\Merchant\RegionMerchantFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiOnlineGateway\MerchantResource;
use App\Http\Resources\ApiOnlineGateway\MerchantTagResource;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantsController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $query = Merchant::query()
            ->whereHas('application_conditions', function ($query) {
                $query->where('active', true);
            })
            ->with('tags')
            ->active()
            ->filterRequest($request, [
                QMerchantFilter::class,
                RegionMerchantFilter::class,
                TagsFilter::class,
            ])
            ->orderByDesc('recommend')
            ->orderByDesc('current_sales');

        return MerchantResource::collection($query->paginate($request->query('per_page') ?? 15));
    }

    public function show($id): MerchantResource
    {
        $merchant = Merchant::query()
            ->with('tags')
            ->active()
            ->findOrFail((int) $id);

        return new MerchantResource($merchant);
    }

    public function tags(Request $request): JsonResource
    {
        $query = Tag::query();

        return MerchantTagResource::collection($query->paginate($request->query('per_page') ?? 15));
    }
}
