<?php

namespace App\Http\Controllers\ApiGateway\ApiAlifshopMerchants\AlifshopMerchants;

use App\Exceptions\BusinessException;
use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\HttpServices\Core\CoreService;
use App\Modules\AlifshopMerchants\DTO\AlifshopMerchantDTO;
use App\Modules\AlifshopMerchants\Models\AlifshopMerchant;
use App\Modules\AlifshopMerchants\Services\AlifshopMerchantService;
use App\Modules\Companies\Models\Company;
use  Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class AlifshopMerchantsController extends ApiBaseController
{
    public function index(Request $request)
    {
        $alifshop_merchants = AlifshopMerchant::query()
            ->filterRequest($request);
            //->orderRequest($request);

        return $alifshop_merchants->paginate($request->query('per_page') ?? 15);
    }

    public function show($id)
    {
        return AlifshopMerchant::query()->findOrFail($id);
    }

    public function store(Request $request, AlifshopMerchantService $alifshopMerchantService)
    {
        $this->validate($request, [
            'company_id' => 'required|integer'
        ]);

        $company = Company::query()->findOrFail($request->input('company_id'));

        $alifshop_merchant = $alifshopMerchantService->create(new AlifshopMerchantDTO(
            maintainer_id: $this->user->id,
            company_id: $company->id
        ));

        Cache::tags($alifshop_merchant->id)->flush();
        Cache::tags('alifshop_merchants')->flush();

        return $alifshop_merchant;
    }

    public function update(Request $request, $alifshop_merchant_id)
    {
        $this->validate($request, [
            'active' => 'required|boolean'
        ]);
        $alifshop_merchant = AlifshopMerchant::query()->findOrFail($alifshop_merchant_id);
        $alifshop_merchant->update($request->all());

        Cache::tags($alifshop_merchant->id)->flush();
        Cache::tags('alifshop_merchants')->flush();

        return $alifshop_merchant;
    }

    public function setMaintainer($id , Request $request)
    {
        $this->validate($request, [
            'maintainer_id' => 'required|integer'
        ]);

        $user = CoreService::getUserById($request->input('maintainer_id'));

        if (!$user)
            throw new BusinessException('Пользователь не найден', 'user_not_exists', 404);

        $alifshop_merchant = AlifshopMerchant::query()->findOrFail($id);
        $alifshop_merchant->maintainer_id = $request->input('maintainer_id');
        $alifshop_merchant->save();

        return $alifshop_merchant;
    }
}
