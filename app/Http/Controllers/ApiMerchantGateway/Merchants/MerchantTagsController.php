<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiMerchantGateway\Merchants;

use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\Tag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class MerchantTagsController extends Controller
{
    public function index(Request $request): LengthAwarePaginator
    {
        return Tag::query()->filterRequest($request, [])
            ->paginate($request->query('per_page') ?? 15);
    }
}
