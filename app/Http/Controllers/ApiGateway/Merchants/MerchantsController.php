<?php

namespace App\Http\Controllers\ApiGateway\Merchants;

use App\Exceptions\BusinessException;
use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\Files\StoreFileRequest;
use App\HttpServices\Telegram\TelegramService;
use App\Modules\Merchants\Models\AdditionalAgreement;
use App\Modules\Merchants\Models\Merchant;
use App\Services\Alifshop\AlifshopService;
use App\Services\Core\ServiceCore;
use App\Services\User;
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
        $merchants = Merchant::query()->with(['stores', 'tags'])
            ->filterRequest($request)
            ->orderRequest($request);

        if ($request->query('object') == 'true') {
            return $merchants->first();
        }
        return $merchants->paginate($request->query('per_page'));
    }

    public function show($id)
    {
        return Merchant::with(['stores', 'tags'])->findOrFail($id);
    }

    public function store(Request $request)
    {
        $validated_data = $this->validate($request, [
            'name' => 'required|max:255|unique:merchants',
            'legal_name' => 'nullable|max:255',
            'token' => 'required|max:255|unique:merchants',
            'alifshop_slug' => 'required|max:255|unique:merchants',
            'information' => 'nullable',
        ]);

        $merchant = new Merchant($validated_data);
        $merchant->maintainer_id = $this->user->prm_admin->id;
        $merchant->save();

        $this->alifshopService->storeOrUpdateMerchant($merchant);
        return $merchant;
    }

    public function update(Request $request, $merchant_id)
    {
        $this->validate($request, [
            'name' => 'required|max:255|unique:merchants,name,' . $merchant_id,
            'legal_name' => 'nullable|max:255',
            'token' => 'required|max:255|unique:merchants,alifshop_slug,' . $merchant_id,
            'alifshop_slug' => 'required|max:255|unique:merchants,alifshop_slug,' . $merchant_id,
            'information' => 'nullable|string',
        ]);

        $merchant = Merchant::query()->findOrFail($merchant_id);
        $oldToken = $merchant->token;
        $merchant->update($request->all());
        $merchant->old_token = $oldToken;

        $this->alifshopService->storeOrUpdateMerchant($merchant);

        return $merchant;
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

    public function updateModules($id, Request $request) //todo make it right
    {
        $validatedRequest = $this->validate($request, [
            'has_deliveries' => 'nullable|boolean',
            'has_manager' => 'nullable|boolean',
            'has_applications' => 'nullable|boolean',
            'has_orders' => 'nullable|boolean',
        ]);

        $merchant = Merchant::query()->findOrFail($id);
        $permissions_switch = [];

        if ($request->has('has_deliveries') && $request->input('has_deliveries') == false) {
            $permissions_switch['permission_deliveries'] = false;
        }
        if ($request->has('has_manager') && $request->input('has_manager') == false) {
            $permissions_switch['permission_manager'] = false;
        }
        if ($request->has('has_applications') && $request->input('has_applications') == false) {
            $permissions_switch['permission_applications'] = false;
        }
        if ($request->has('has_orders') && $request->input('has_orders') == false) {
            $permissions_switch['permission_orders'] = false;
        }

        DB::transaction(function () use ($merchant, $validatedRequest, $permissions_switch) {
            $merchant->update($validatedRequest);
            $merchant->merchant_users()->update($permissions_switch);
        });

        Cache::forget('merchant_module_applications_middleware_' . $merchant->id);
        Cache::forget('merchant_module_deliveries_middleware_' . $merchant->id);
        Cache::forget('merchant_module_manager_middleware_' . $merchant->id);
        Cache::forget('merchant_module_orders_middleware_' . $merchant->id);

        return $merchant;
    }

    public function setResponsibleUser($id, Request $request)
    {
        $this->validate($request, [
            'maintainer_id' => 'required|integer'
        ]);

        $user = ServiceCore::request('GET', 'users', new Request([
            'user_id' => $request->input('maintainer_id'),
            'object' => 'true'
        ]));

        if(!$user)
            throw new BusinessException('Пользователь не найден', 'user_not_exists', 404);

        $merchant = Merchant::findOrFail($id);
        $merchant->maintainer_id = $request->maintainer_id;
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

        $merchant->stores()->where('id', '<>', $request->store_id)->update([
            'is_main' => false
        ]);
        return $merchant;
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

    public function hotMerchants(Request $request)
    {
        $percentage_of_limit = Merchant::$percentage_of_limit;
        $additional_agreement_table_name = with(new AdditionalAgreement())->getTable();
        $merchant_query = DB::table('merchants')->select([
            'merchants.id',
            'merchants.name',
            DB::raw("sum($additional_agreement_table_name.limit) as agreement_sum"),
            'merchants.current_sales',
            'merchant_infos.limit'
        ])
            ->leftJoin('merchant_infos', 'merchants.id', '=', 'merchant_infos.merchant_id')
            ->leftJoin("$additional_agreement_table_name", 'merchants.id', '=', "$additional_agreement_table_name.merchant_id")
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

}


