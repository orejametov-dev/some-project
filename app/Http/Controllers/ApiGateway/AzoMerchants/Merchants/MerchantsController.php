<?php

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\Exceptions\BusinessException;
use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\Files\StoreFileRequest;
use App\HttpServices\Auth\AuthMicroService;
use App\HttpServices\Telegram\TelegramService;
use App\HttpServices\Warehouse\WarehouseService;
use App\Modules\Companies\Models\Company;
use App\Modules\Companies\Models\Module;
use App\Modules\Companies\Services\CompanyService;
use App\Modules\Merchants\DTO\Merchants\MerchantsDTO;
use App\Modules\Merchants\Models\ActivityReason;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Tag;
use App\Modules\Merchants\Services\Merchants\MerchantsService;
use App\Services\Alifshop\AlifshopService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MerchantsController extends ApiBaseController
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
        $merchants = Merchant::query()->with(['stores', 'tags' ,'company'])
            ->filterRequest($request)
            ->orderRequest($request);

        if ($request->query('object') == 'true') {
            return $merchants->first();
        }
        return $merchants->paginate($request->query('per_page'));
    }

    public function show($id)
    {
        return Merchant::with(['stores', 'tags', 'activity_reasons', 'company'])->findOrFail($id);
    }

    public function store(Request $request, MerchantsService $merchantsService, CompanyService $companyService)
    {
        $this->validate($request, [
            'company_id' => 'required|integer'
        ]);

        $company = Company::query()->findOrFail($request->input('company_id'));

        if(Merchant::query()->where('company_id', $company->id)->exists()){
            return response()->json(['message' => 'Указаная компания уже имеет аъзо модуль'], 400);
        }

        $merchant = $merchantsService->create(new MerchantsDTO(
            id: $company->id,
            name: $company->name,
            legal_name: $company->legal_name,
            legal_name_prefix: $company->legal_name_prefix,
            information: null,
            maintainer_id: $this->user->id,
            company_id: $company->id
        ));

        $company->modules()->attach([Module::AZO_MERCHANT]);

        Cache::tags($merchant->id)->flush();
        Cache::tags('azo_merchants')->flush();
        Cache::tags('company')->flush();

        $this->alifshopService->storeOrUpdateMerchant($merchant->fresh());
        return $merchant;
    }

    public function update(Request $request, $merchant_id)
    {
        $this->validate($request, [
            'name' => 'required|max:255|unique:merchants,name,' . $merchant_id,
            'legal_name' => 'nullable|max:255',
            'legal_name_prefix' => 'nullable|string',
            'token' => 'required|max:255|unique:merchants,alifshop_slug,' . $merchant_id,
            'alifshop_slug' => 'required|max:255|unique:merchants,alifshop_slug,' . $merchant_id,
            'information' => 'nullable|string',
            'min_application_price' => 'required|integer'
        ]);

        $merchant = Merchant::query()->findOrFail($merchant_id);
        $oldToken = $merchant->token;
        $merchant->update($request->all());
        $merchant->old_token = $oldToken;

        Company::query()
            ->findOrFail($merchant->company_id)
            ->update(['legal_name_prefix' => $request->input('legal_name_prefix')]);

        Cache::tags($merchant->id)->flush();
        Cache::tags('azo_merchants')->flush();
        Cache::tags('company')->flush();
        $this->alifshopService->storeOrUpdateMerchant($merchant);

        return $merchant->load(['company']);
    }

    public function uploadLogo($merchant_id, StoreFileRequest $request)
    {
        $merchant = Merchant::query()->findOrFail($merchant_id);
        $merchant->uploadLogo($request->file('file'));

        $this->alifshopService->storeOrUpdateMerchant($merchant);
        return $merchant;
    }

    public function removeLogo($merchant_id)
    {
        $merchant = Merchant::query()->findOrFail($merchant_id);
        $merchant->deleteLogo();

        $this->alifshopService->storeOrUpdateMerchant($merchant);
        return response()->json(['message' => 'Логотип удалён']);
    }

    public function updateChatId($merchant_id)
    {
        $merchant = Merchant::findOrFail($merchant_id);

        $updates = TelegramService::getUpdates([]);
        foreach ($updates['result'] as $update) {
            if (array_key_exists('message', $update) && array_key_exists('text', $update['message']) && $update['message']['text'] == '/token ' . $merchant->token) {
                $merchant->telegram_chat_id = $update['message']['chat']['id'];
                $merchant->save();
            }
        }

        return response()->json(['message' => 'Обновлено']);
    }

    public function setResponsibleUser($id, Request $request)
    {
        $this->validate($request, [
            'maintainer_id' => 'required|integer'
        ]);

        $user = AuthMicroService::getUserById($request->input('maintainer_id'));

        if (!$user)
            throw new BusinessException('Пользователь не найден', 'user_not_exists', 404);

        $merchant = Merchant::query()->findOrFail($id);
        $merchant->maintainer_id = $request->input('maintainer_id');
        $merchant->save();

        return $merchant;
    }

    public function setMainStore($id, Request $request)
    {
        $this->validate($request, [
            'store_id' => 'required|integer|min:0'
        ]);
        $merchant = Merchant::query()->findOrFail($id);

        $merchant->stores()->findOrFail($request->store_id)->update([
            'is_main' => true
        ]);

        $merchant->stores()->where('id', '<>', $request->input('store_id'))->update([
            'is_main' => false
        ]);
        return $merchant;
    }

    public function setTags($id, Request $request)
    {
        $this->validate($request, [
            'tags' => 'required|array'
        ]);
        $merchant = Merchant::query()->findOrFail($id);
        $tags = $request->input('tags');

        $tags = Tag::whereIn('id', $tags)->get();

        foreach ($request->input('tags') as $tag) {
            if(!$tags->contains('id', $tag)){
                return response()->json(['message' => 'Указан не правильный тег'], 400);
            }
        }

        $merchant->tags()->sync($tags);

        return $merchant;
    }

    public function hotMerchants()
    {
        $percentage_of_limit = Merchant::$percentage_of_limit;

        $merchant_query = DB::table('merchants')->select([
            'merchants.id',
            'merchants.name',
            DB::raw('sum(merchant_additional_agreements.limit) as agreement_sum'),
            'merchants.current_sales',
            'merchant_infos.limit'
        ])
            ->leftJoin('merchant_infos', 'merchants.id', '=', 'merchant_infos.merchant_id')
            ->leftJoin('merchant_additional_agreements', 'merchants.id', '=', 'merchant_additional_agreements.merchant_id')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('merchant_infos')
                    ->whereColumn('merchants.id', 'merchant_infos.merchant_id');
            })
            ->groupBy(['merchants.id', 'merchants.name', 'merchant_infos.limit']);

        return DB::table(DB::raw("({$merchant_query->toSql()}) as sub_query"))
            ->select([
                'sub_query.id',
                'sub_query.name'
            ])->whereRaw("(IFNULL(sub_query.limit, 0) + IFNULL(sub_query.agreement_sum, 0)) $percentage_of_limit <= sub_query.current_sales")->get();
    }

    public function toggle($id, Request $request)
    {
        $this->validate($request, [
            'activity_reason_id' => 'integer|required'
        ]);

        $activity_reason = ActivityReason::where('type', 'MERCHANT')
            ->findOrFail($request->input('activity_reason_id'));

        $merchant = Merchant::findOrFail($id);
        $merchant->active = !$merchant->active;
        $merchant->save();

        $merchant->activity_reasons()->attach($activity_reason->id, [
            'active' => $merchant->active,
            'created_by_id' => $this->user->id,
            'created_by_name' => $this->user->name
        ]);

        $merchant->company->modules()->updateExistingPivot(Module::AZO_MERCHANT, ['active' => $merchant->active]);

        Cache::tags($merchant->id)->flush();
        Cache::tags('merchants')->flush();
        return $merchant;
    }

    public function toggleGeneralGoods($id, Request $request)
    {
        $merchant = Merchant::findOrFail($id);
        $merchant->has_general_goods = !$merchant->has_general_goods;

        WarehouseService::checkDuplicateSKUs($merchant->id);

        $merchant->save();

        Cache::tags($merchant->id)->flush();
        Cache::tags('azo_merchants')->flush();
        Cache::tags('company')->flush();
        return $merchant;
    }

}


