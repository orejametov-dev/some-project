<?php


namespace App\Http\Controllers\ApiReportGateway\Merchants;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiReportGateway\Merchants\MerchantsResource;
use App\Modules\Merchants\Models\Merchant;
use Illuminate\Http\Request;

class MerchantsController extends Controller
{
    public function index(Request $request)
    {
        $query = Merchant::query();

        return MerchantsResource::collection($query->paginate($request->query('per_page')));
    }
}
