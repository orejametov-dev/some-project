<?php


namespace App\Http\Controllers\ApiMerchantGateway\Merchants;


use App\Http\Controllers\ApiMerchantGateway\ApiBaseController;
use App\Modules\Merchants\Models\Tag;
use Illuminate\Http\Request;

class MerchantTagsController extends ApiBaseController
{
    public function index(Request $request)
    {
        $tags = Tag::query()->filterRequests($request);

        if ($request->has('object') && $request->query('object') == true) {
            return $tags->first();
        }

        if($request->has('paginate') && $request->query('paginate') == false) {
            return $tags->get();
        }

        return $tags->paginate($request->query('per_page') ?? 15);
    }
}
