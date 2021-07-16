<?php


namespace App\Http\Controllers\ApiMerchantGateway\Merchants;


use App\Http\Controllers\ApiMerchantGateway\ApiBaseController;
use App\Modules\Merchants\Models\Tag;
use Illuminate\Http\Request;

class MerchantTagsController extends ApiBaseController
{
    public function index(Request $request)
    {
        return Tag::query()->filterRequests($request)
            ->paginate($request->query('per_page') ?? 15);
    }
}
