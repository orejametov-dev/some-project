<?php

namespace App\Http\Controllers\ApiGateway\ApiAlifshopMerchants\AlifshopMerchants;

use App\Exceptions\BusinessException;
use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\HttpServices\Core\CoreService;
use App\Modules\AlifshopMerchants\Models\AlifshopMerchant;
use App\Modules\Companies\DTO\CompanyDTO;
use App\Modules\Companies\Services\CompanyService;
use Illuminate\Http\Request;

class AlifshopMerchantsController extends ApiBaseController
{

    public function index(Request $request)
    {
        $merchants = AlifshopMerchant::query()
            ->filterRequest($request)
            ->orderRequest($request);

        return $merchants->paginate($request->query('per_page'));
    }

    public function show($id)
    {
        return AlifshopMerchant::query()->findOrFail($id);
    }

    public function store(Request $request, CompanyService $companyService)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'legal_name' => 'required|max:255',
        ]);

        $company = $companyService->create(new CompanyDTO(
            name: $request->input('name'),
            legal_name: $request->input('legal_name')
        ));

        $alifshop_merchant = 'hello';



        return $alifshop_merchant;
    }

    public function update(Request $request, $alifshop_merchant_id)
    {
        $this->validate($request, [
            'active' => 'required|boolean'
        ]);
        $alifshop_merchant = AlifshopMerchant::query()->findOrFail($alifshop_merchant_id);

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
