<?php


namespace App\Http\Controllers\ApiOnlineGateway\Stores;


use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\Store;
use Illuminate\Http\Request;

class StoresController extends Controller
{
    public function index(Request $request)
    {
        $stores = Store::query()
            ->azo()
            ->active()
            ->filterRequest($request);

        return $stores->paginate($request->query('per_page'));
    }
}
