<?php

namespace App\Http\Controllers\ApiGateway\ApiAlifshopMerchants\AlifshopMerchants;

use App\Exceptions\BusinessException;
use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\AlifshopMerchant\AlifshopMerchantStoreFileRequest;
use App\HttpServices\Auth\AuthMicroService;
use App\HttpServices\Company\CompanyService;
use App\Modules\AlifshopMerchants\DTO\AlifshopMerchantDTO;
use App\Modules\AlifshopMerchants\Models\AlifshopMerchant;
use App\Modules\AlifshopMerchants\Services\AlifshopMerchantService;
use App\Modules\Merchants\Models\ActivityReason;
use App\Modules\Merchants\Models\Store;
use App\Modules\Merchants\Models\Tag;
use App\Services\Alifshop\AlifshopService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
            ->with(['tags'])
            ->filterRequest($request)
            ->orderRequest($request);

        return $alifshop_merchants->paginate($request->query('per_page') ?? 15);
    }

    public function show($id)
    {
        return AlifshopMerchant::query()
            ->with(['tags'])
            ->findOrFail($id);
    }

    public function store(Request $request, AlifshopMerchantService $alifshopMerchantService)
    {
        $this->validate($request, [
            'company_id' => 'required|integer'
        ]);

        $company = CompanyService::getCompanyById($request->input('company_id'));

        if (AlifshopMerchant::query()->where('company_id', $company->id)->exists()) {
            return response()->json(['message' => 'Указаная компания уже имеет алифшоп модуль'], 400);
        }

        $alifshop_merchant = $alifshopMerchantService->create(new AlifshopMerchantDTO(
            id: $company['id'],
            name: $company['name'],
            legal_name: $company['legal_name'],
            information: null,
            maintainer_id: $this->user->id,
            company_id: $company['id']
        ));

        $company->modules()->attach([Module::ALIFSHOP_MERCHANT]);

        Store::query()
            ->where('merchant_id', $alifshop_merchant->id)
            ->update(['is_alifshop' => true]);

        Cache::tags($alifshop_merchant->id)->flush();
        Cache::tags('alifshop_merchants')->flush();
        Cache::tags('company')->flush();

        $this->alifshopService->storeOrUpdateMerchant($alifshop_merchant->fresh());

        return $alifshop_merchant;
    }

    public function update(Request $request, $alifshop_merchant_id)
    {
        $validatedData = $this->validate($request, [
            'name' => 'required|max:255|unique:alifshop_merchants,name,' . $alifshop_merchant_id,
            'legal_name' => 'nullable|max:255',
            'legal_name_prefix' => 'nullable|string',
            'token' => 'required|max:255|unique:alifshop_merchants,alifshop_slug,' . $alifshop_merchant_id,
            'alifshop_slug' => 'required|max:255|unique:alifshop_merchants,alifshop_slug,' . $alifshop_merchant_id,
            'information' => 'nullable|string',
        ]);

        $alifshop_merchant = AlifshopMerchant::query()->findOrFail($alifshop_merchant_id);
        $oldToken = $alifshop_merchant->token;
        $alifshop_merchant->update($validatedData);
        $alifshop_merchant->old_token = $oldToken;


        Cache::tags($alifshop_merchant->id)->flush();
        Cache::tags('alifshop_merchants')->flush();
        Cache::tags('company')->flush();

        $this->alifshopService->storeOrUpdateMerchant($alifshop_merchant);

        return $alifshop_merchant;
    }

    public function setMaintainer($id, Request $request)
    {
        $this->validate($request, [
            'maintainer_id' => 'required|integer'
        ]);

        $user = AuthMicroService::getUserById($request->input('maintainer_id'));

        if (!$user)
            throw new BusinessException('Пользователь не найден', 'user_not_exists', 404);

        $alifshop_merchant = AlifshopMerchant::query()->findOrFail($id);
        $alifshop_merchant->maintainer_id = $request->input('maintainer_id');
        $alifshop_merchant->save();

        return $alifshop_merchant;
    }

    //Артем
    //Добавить лого
    public function uploadLogo($alifshop_merchant_id, AlifshopMerchantStoreFileRequest $request)
    {
        $alifshop_merchant = AlifshopMerchant::query()->findOrFail($alifshop_merchant_id);
        $alifshop_merchant->uploadLogo($request->file('file'));

        $this->alifshopService->storeOrUpdateMerchant($alifshop_merchant);
        return $alifshop_merchant;
    }

    public function removeLogo($alifshop_merchant_id)
    {
        $alifshop_merchant = AlifshopMerchant::query()->findOrFail($alifshop_merchant_id);
        $alifshop_merchant->deleteLogo();

        $this->alifshopService->storeOrUpdateMerchant($alifshop_merchant);
        return response()->json(['message' => 'Логотип удалён']);
    }

    public function toggle($id, Request $request)
    {
        $this->validate($request, [
            'activity_reason_id' => 'integer|required'
        ]);

        $activity_reason = ActivityReason::where('type', 'MERCHANT')
            ->findOrFail($request->input('activity_reason_id'));

        $alifshop_merchant = AlifshopMerchant::findOrFail($id);
        $alifshop_merchant->active = !$alifshop_merchant->active;
        $alifshop_merchant->save();

        $alifshop_merchant->activity_reasons()->attach($activity_reason->id, [
            'active' => $alifshop_merchant->active,
            'created_by_id' => $this->user->id,
            'created_by_name' => $this->user->name
        ]);

        $alifshop_merchant->company->modules()->updateExistingPivot(Module::ALIFSHOP_MERCHANT, ['active' => $alifshop_merchant->active]);

        Cache::tags($alifshop_merchant->id)->flush();
        Cache::tags('alifshop_merchants')->flush();
        Cache::tags('company')->flush();

        return $alifshop_merchant;
    }

    public function setTags($id, Request $request)
    {
        $this->validate($request, [
            'tags' => 'required|array'
        ]);

        $tags = Tag::whereIn('id', $request->input('tags'))->get();

        foreach ($request->input('tags') as $tag) {
            if (!$tags->contains('id', $tag)) {
                return response()->json(['message' => 'Указан не правильный тег'], 400);
            }
        }

        $alifshop_merchant = AlifshopMerchant::query()->findOrFail($id);
        $tags = $request->input('tags');

        $alifshop_merchant->tags()->sync($tags);

        return $alifshop_merchant;
    }
}
