<?php

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\DTOs\Merchants\UpdateMerchantDTO;
use App\Exceptions\ApiBusinessException;
use App\Exceptions\BusinessException;
use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\Competitors\CompetitorsRequest;
use App\Http\Requests\ApiPrm\Files\StoreFileRequest;
use App\Http\Requests\ApiPrm\Merchants\StoreMerchantRequest;
use App\Http\Requests\ApiPrm\Merchants\UpdateMerchantRequest;
use App\HttpServices\Auth\AuthMicroService;
use App\HttpServices\Company\CompanyService;
use App\HttpServices\Telegram\TelegramService;
use App\HttpServices\Warehouse\WarehouseService;
use App\Modules\Merchants\Models\ActivityReason;
use App\Modules\Merchants\Models\Competitor;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Tag;
use App\UseCases\Merchants\StoreMerchantUseCase;
use App\UseCases\Merchants\UpdateMerchantUseCase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MerchantsController extends ApiBaseController
{
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
        return Merchant::with(['stores', 'tags', 'activity_reasons', 'competitors'])->findOrFail($id);
    }

    public function store(StoreMerchantRequest $request, StoreMerchantUseCase $storeMerchantUseCase)
    {
        $merchant = $storeMerchantUseCase->execute(
            company_id: (int)$request->input('company_id')
        );

        return $merchant;
    }

    public function update($id, UpdateMerchantRequest $request, UpdateMerchantUseCase $updateMerchantUseCase)
    {
        $updateMerchantDTO = new UpdateMerchantDTO(
            id: (int) $id,
            name: (string)$request->input('name'),
            legal_name: $request->input('legal_name') ? (string)$request->input('legal_name') : null,
            legal_name_prefix: $request->input('legal_name_prefix') ? (string)$request->input('legal_name_prefix') : null,
            token: (string)$request->input('token'),
            alifshop_slug: (string)$request->input('alifshop_slug'),
            information: $request->input('information') ? (string)$request->input('information') : null,
            min_application_price: (int)$request->input('min_application_price')
        );
        $merchant = $updateMerchantUseCase->execute($updateMerchantDTO);
        return $merchant;
    }

    public function uploadLogo($merchant_id, StoreFileRequest $request)
    {
        $merchant = Merchant::query()->findOrFail($merchant_id);
        $merchant->uploadLogo($request->file('file'));

        return $merchant;
    }

    public function removeLogo($merchant_id)
    {
        $merchant = Merchant::query()->findOrFail($merchant_id);
        $merchant->deleteLogo();

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
            if (!$tags->contains('id', $tag)) {
                return response()->json(['message' => 'Указан не правильный тег'], 400);
            }
        }

        $merchant->tags()->sync($tags);

        return $merchant;
    }

    public function hotMerchants()
    {
        $percentage_of_limit = Merchant::$percentage_of_limit;

        $merchant_query = DB::table('merchants')
            ->whereRaw('active = 1')
            ->select([
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

        CompanyService::setStatusNotActive($merchant->company_id);

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

    public function toggleRecommend($id)
    {
        $merchant = Merchant::findOrFail($id);
        $merchant->recommend = !$merchant->recommend;
        $merchant->save();

        Cache::tags($merchant->id)->flush();
        Cache::tags('azo_merchants')->flush();

        return $merchant;
    }

    public function attachCompetitor($id, CompetitorsRequest $request)
    {
        $merchant = Merchant::query()->findOrFail($id);
        $competitor = Competitor::query()->findOrFail($request->input('competitor_id'));

        if ($merchant->competitors()->find($competitor->id)) {
            throw new ApiBusinessException('Информация о данном конкуренте на этого мерчанта уже была создана', 'merchant_competitor_exists', [
                'ru' => 'Информация о данном конкуренте на этого мерчанта уже была создана',
                'uz' => 'Merchantdagi bu konkurent haqidagi ma\'lumot qo\'shib bo\'lingan ekan'
            ], 400);
        }

        $merchant->competitors()->attach($competitor->id, [
            'volume_sales' => $request->input('volume_sales') * 100,
            'percentage_approve' => $request->input('percentage_approve'),
            'partnership_at' => Carbon::parse($request->input('partnership_at'))->format('Y-m-d H:i:s'),
        ]);

        return $merchant->load('competitors');
    }

    public function updateCompetitor($id, CompetitorsRequest $request)
    {
        $merchant = Merchant::query()->findOrFail($id);
        $competitor = Competitor::query()->findOrFail($request->input('competitor_id'));

        $merchant->competitors()->findOrFail($competitor->id);
        $merchant->competitors()->detach($competitor->id);
        $merchant->competitors()->attach($competitor->id, [
            'volume_sales' => $request->input('volume_sales') * 100,
            'percentage_approve' => $request->input('percentage_approve'),
            'partnership_at' => Carbon::parse($request->input('partnership_at'))->format('Y-m-d H:i:s'),
        ]);


        return $merchant->load('competitors');
    }

    public function detachCompetitor($id, Request $request)
    {
        $merchant = Merchant::query()->findOrFail($id);
        $competitor = Competitor::query()->findOrFail($request->input('competitor_id'));

        $merchant->competitors()->findOrFail($competitor->id);

        $merchant->competitors()->detach($competitor->id);

        return response()->json(['message' => 'Данные о конкуренте были удалены у этого мерчанта']);
    }
}

