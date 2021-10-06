<?php

namespace App\Http\Controllers\ApiGateway\ApiAlifshopMerchants\AlifshopMerchants;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\HttpServices\Core\CoreService;
use App\Modules\AlifshopMerchants\Models\AlifshopMerchantAccess;
use App\Modules\AlifshopMerchants\Models\AlifshopMerchantStores;
use App\Modules\Companies\Models\CompanyUser;
use Illuminate\Http\Request;

class AlifshopMerchantAccessController extends Controller
{
    public function index(Request $request)
    {
        $alifshop_merchant_accesses = AlifshopMerchantAccess::query()
            ->filterRequest($request)
            ->orderRequest($request);

        return $alifshop_merchant_accesses->paginate($request->query('per_page') ?? 15);
    }

    public function show($id)
    {
        return AlifshopMerchantAccess::query()->findOrFail($id);
    }

    public function store(Request $request)
    {
        $user = CoreService::getUserById($request->input('user_id'));

        if (!$user)
            throw new BusinessException('Пользователь не найден', 'user_not_exists', 404);

        $alifshop_merchant_store = AlifshopMerchantStores::query()->findOrFail($request->input('store_id'));

        $company_user = CompanyUser::query()->where('user_id', $user->id)->firstOrNew();
        $company_user->user_id = $user->id;
        $company_user->company_id = $alifshop_merchant_store->alifshopMerchant->company->id;
        $company_user->save();
    }

    public function update()
    {

    }

    public function setMaintainer()
    {

    }
}
