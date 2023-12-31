<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiOnlineGateway\Merchants;

use App\Filters\CommonFilters\TagsFilter;
use App\Filters\Merchant\IntegrationFilter;
use App\Filters\Merchant\QMerchantFilter;
use App\Filters\Merchant\RecommendFilter;
use App\Filters\Merchant\RegionMerchantFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiOnlineGateway\MerchantResource;
use App\Http\Resources\ApiOnlineGateway\MerchantTagResource;
use App\Models\Merchant;
use App\Models\Tag;
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
                IntegrationFilter::class,
                RecommendFilter::class,
            ])
            ->orderByDesc('recommend')
            ->orderByDesc('current_sales');

        return MerchantResource::collection($query->paginate($request->query('per_page') ?? 30));
    }

    public function show(int $id): MerchantResource
    {
        $merchant = Merchant::query()
            ->with('tags')
            ->active()
            ->findOrFail($id);

        return new MerchantResource($merchant);
    }

    public function tags(Request $request): JsonResource
    {
        $query = Tag::query();

        return MerchantTagResource::collection($query->paginate($request->query('per_page') ?? 15));
    }
}
