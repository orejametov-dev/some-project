<?php


namespace App\Http\Controllers\Api\Merchants;


use App\Modules\Merchants\Models\MerchantUser;
use Illuminate\Http\Request;

class MerchantUsersController
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'relations' => 'nullable|array'
        ]);

        $merchant_users = MerchantUser::with($request->query('relations') ?? [])->filterRequest($request);

        if ($request->query('object') == 'true') {
            return $merchant_users->first();
        }
        return $merchant_users->paginate($request->query('per_page'));
    }

    public function show(Request $request, $id)
    {
        $this->validate($request, [
            'relations' => 'nullable|array'
        ]);

        return MerchantUser::with($request->query('relations') ?? [])->findOrFail($id);
    }
}
