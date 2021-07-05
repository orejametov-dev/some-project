<?php


namespace App\Http\Controllers\ApiCreditsGateway\Merchants;


use App\Http\Controllers\ApiCreditsGateway\ApiBaseController;
use App\Http\Resources\ApiCredtisGateway\Merchants\MerchantsResource;
use App\Http\Resources\ApiMerchantGateway\ProblemCases\ProblemCaseResource;
use App\Modules\Merchants\Models\Merchant;
use DB;
use Illuminate\Http\Request;

class MerchantsController extends ApiBaseController
{
    public function index(Request $request)
    {
        $query = Merchant::query()
            ->filterRequest($request)
            ->latest();

        if ($request->query('object') == true) {
            return new MerchantsResource($query->first());
        }

        if($request->has('paginate') && $request->query('paginate') == 0){
            return MerchantsResource::collection($query->get() ?? 15);
        }

        return MerchantsResource::collection($query->paginate($request->query('per_page')));
    }

    public function indexSpecial(Request $request)
    {
        //Заказный роут от Рамиль ака
        $query = Merchant::query()
            ->select([
                DB::raw('group_concat(id) as merchant_ids'),
                'legal_name'
            ])
            ->filterRequest($request)
            ->groupBy('legal_name');

        if ($request->query('object') == true) {
            return $query->first();
        }

        if($request->has('paginate') && $request->query('paginate') == 0){
            return $query->get();
        }

        return $query->paginate($request->query('per_page'));
    }
}
