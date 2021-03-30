<?php


namespace App\Http\Controllers\ApiGateway\Online;



use App\Http\Controllers\Controller;
use App\Http\Resources\OnlineGateway\MerchantResource;
use App\Http\Resources\OnlineGateway\MerchantTagResource;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Tag;
use Illuminate\Http\Request;

class MerchantsController extends Controller
{
    public function index(Request $request)
    {
        $query = Merchant::query()
            ->filterRequest($request);

        return MerchantResource::collection($query->paginate($request->query('per_page')));
    }

    public function tags(Request $request)
    {
        $query = Tag::query();

        if ($q = $request->query('q')) {
            $query->where('title', 'like', "%$q%");
        }

        return MerchantTagResource::collection($query->paginate($request->query('per_page')));
    }
}
