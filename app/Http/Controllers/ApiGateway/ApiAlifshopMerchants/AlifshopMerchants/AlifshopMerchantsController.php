<?php

namespace App\Http\Controllers\ApiGateway\ApiAlifshopMerchants\AlifshopMerchants;

use App\Exceptions\BusinessException;
use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\HttpServices\Core\CoreService;
use App\Modules\AlifshopMerchants\DTO\AlifshopMerchantDTO;
use App\Modules\AlifshopMerchants\Models\AlifshopMerchant;
use App\Modules\AlifshopMerchants\Services\AlifshopMerchantService;
use App\Modules\Companies\Models\Company;
use App\Modules\Merchants\Models\Merchant;
use App\Services\Alifshop\AlifshopService;
use  Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class AlifshopMerchantsController extends ApiBaseController
{
    /**
     * @var AlifshopService
     */
    private $alifshopService;

    public function __construct(AlifshopService $alifshopService)
    {
        parent::__construct();
        $this->alifshopService = $alifshopService;
    }
    public function index(Request $request)
    {
        $alifshop_merchants = AlifshopMerchant::query()
            ->filterRequest($request)
            ->orderRequest($request);

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
            id: $company->id,
            name: $company->name,
            legal_name: $company->legal_name,
            information: null,
            maintainer_id: $this->user->id,
            company_id: $company->id
        ));

        Cache::tags($alifshop_merchant->id)->flush();
        Cache::tags('alifshop_merchants')->flush();

        $this->alifshopService->storeOrUpdateMerchant($alifshop_merchant->fresh());

        return $alifshop_merchant;
    }

    public function update(Request $request, $alifshop_merchant_id)
    {
        $this->validate($request, [
            'name' => 'required|max:255|unique:alifshop_merchants,name,' . $alifshop_merchant_id,
            'legal_name' => 'nullable|max:255',
            'token' => 'required|max:255|unique:alifshop_merchants,alifshop_slug,' . $alifshop_merchant_id,
            'alifshop_slug' => 'required|max:255|unique:alifshop_merchants,alifshop_slug,' . $alifshop_merchant_id,
            'information' => 'nullable|string',
            'min_application_price' => 'required|integer'
        ]);

        $alifshop_merchant = AlifshopMerchant::query()->findOrFail($alifshop_merchant_id);
        $oldToken = $alifshop_merchant_id->token;
        $alifshop_merchant_id->update($request->all());
        $alifshop_merchant_id->old_token = $oldToken;

        Cache::tags($alifshop_merchant_id->id)->flush();
        Cache::tags('alifshop_merchants')->flush();

        $this->alifshopService->storeOrUpdateMerchant($alifshop_merchant_id);

        return $alifshop_merchant;
    }

    public function setMaintainer($id , Request $request)
    {
        $this->validate($request, [
            'maintainer_id' => 'required|integer'
        ]);

        $user = CoreService::getUserById($request->input('maintainer_id')); //изменить на Auth

        if (!$user)
            throw new BusinessException('Пользователь не найден', 'user_not_exists', 404);

        $alifshop_merchant = AlifshopMerchant::query()->findOrFail($id);
        $alifshop_merchant->maintainer_id = $request->input('maintainer_id');
        $alifshop_merchant->save();

        return $alifshop_merchant;
    }

    //Артем
    //Добавить лого

    //ойбек
    public function toggle($id, Request $request)
    {
        $this->validate($request, [
            'active' => 'required|integer'
        ]);

        $alifshop_merchant = AlifshopMerchant::findOrFail($id);
        $alifshop_merchant->active = !$alifshop_merchant->active;
        $alifshop_merchant->save();

        Cache::tags($alifshop_merchant->id)->flush();
        Cache::tags('alifshop_merchants')->flush();
        return $alifshop_merchant;
    }

    public function setTags(Request $request)
    {
        $this->validate($request, [
            'merchant_id' => 'required|integer',
            'tags' => 'required|array'
        ]);
        $merchant = Merchant::query()->findOrFail($request->input('merchant_id'));
        $tags = $request->input('tags');

        $merchant->tags()->sync($tags);

        return $merchant;
    }
}
