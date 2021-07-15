<?php


namespace App\Http\Controllers\ApiMerchantGateway\Merchants;


use App\Http\Controllers\ApiMerchantGateway\ApiBaseController;
use App\Modules\Merchants\Models\Tag;
use Illuminate\Http\Request;

class MerchantTagsController extends ApiBaseController
{
    public function index(Request $request)
    {
        $merchant_tags = Tag::query()->filterRequests($request);

        if($request->has('paginate') && $request->query('paginate') == false) {
            return $merchant_tags->get();
        }

        return $merchant_tags->paginate($request->query('per_page') ?? 15);
    }
}
