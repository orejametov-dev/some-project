<?php


namespace App\Http\Controllers\Api\Merchants;


use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\Merchant;
use Illuminate\Http\Request;

class MerchantsController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'relations' => 'nullable|array'
        ]);

        $merchants = Merchant::query()->with($request->query('relations') ?? [])
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

        return Merchant::with($request->query('relations') ?? [])->findOrFail($id);
    }

}
