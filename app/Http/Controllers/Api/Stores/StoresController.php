<?php


namespace App\Http\Controllers\Api\Stores;


use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\Store;
use Illuminate\Http\Request;

class StoresController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'relations' => 'nullable|array'
        ]);

        $merchants = Store::query()->with($request->query('relations') ?? [])
            ->filterRequest($request);

        if ($request->query('object') == 'true') {
            return $merchants->first();
        }
        return $merchants->paginate($request->query('per_page'));
    }

    public function show(Request $request, $id)
    {
        $this->validate($request, [
            'relations' => 'nullable|array'
        ]);

        return Store::with($request->query('relations') ?? [])->findOrFail($id);
    }
}
