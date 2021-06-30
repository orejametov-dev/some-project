<?php


namespace App\Http\Controllers\ApiCreditsGateway\Stores;


use App\Http\Controllers\ApiCreditsGateway\ApiBaseController;
use App\Http\Resources\ApiCredtisGateway\Stores\StoresResource;
use App\Modules\Merchants\Models\Store;
use Illuminate\Http\Request;

class StoresController extends ApiBaseController
{
    public function index(Request $request)
    {
        $stores = Store::query()->filterRequest($request);

        if ($request->query('object') == true) {
            return new StoresResource($stores->first());
        }

        if ($request->has('paginate') && $request->query('paginate') == false) {
            return StoresResource::collection($stores->get());
        }

        return StoresResource::collection($stores->paginate($request->query('per_page') ?? 15));
    }
}
