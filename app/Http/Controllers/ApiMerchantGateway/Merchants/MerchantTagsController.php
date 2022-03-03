<?php

namespace App\Http\Controllers\ApiMerchantGateway\Merchants;

use App\Filters\Tag\GTagFilter;
use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\Tag;
use Illuminate\Http\Request;

class MerchantTagsController extends Controller
{
    public function index(Request $request)
    {
        return Tag::query()->filterRequest($request, [GTagFilter::class])
            ->paginate($request->query('per_page') ?? 15);
    }
}
