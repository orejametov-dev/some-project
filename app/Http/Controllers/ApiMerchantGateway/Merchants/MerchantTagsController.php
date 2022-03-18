<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiMerchantGateway\Merchants;

use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantTagsController extends Controller
{
    public function index(Request $request): JsonResource
    {
        return JsonResource::collection(Tag::query()->filterRequest($request, [])
            ->paginate($request->query('per_page') ?? 15));
    }
}
