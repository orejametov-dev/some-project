<?php

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\DTOs\Merchants\UpdateMerchantDTO;
use App\Exceptions\ApiBusinessException;
use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\Competitors\CompetitorsRequest;
use App\Http\Requests\ApiPrm\Files\StoreFileRequest;
use App\Http\Requests\ApiPrm\Merchants\SetMainStoreRequest;
use App\Http\Requests\ApiPrm\Merchants\SetResponsibleUserRequest;
use App\Http\Requests\ApiPrm\Merchants\StoreMerchantRequest;
use App\Http\Requests\ApiPrm\Merchants\UpdateMerchantRequest;
use App\HttpServices\Company\CompanyService;
use App\HttpServices\Warehouse\WarehouseService;
use App\Modules\Merchants\Models\ActivityReason;
use App\Modules\Merchants\Models\Competitor;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Tag;
use App\UseCases\Merchants\FindMerchantUseCase;
use App\UseCases\Merchants\SetMainStoreUseCase;
use App\UseCases\Merchants\SetResponsibleUserUseCase;
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
            ->filterRequests($request)
            ->orderRequest($request);

        if ($request->query('object') == 'true') {
            return $merchants->first();
        }

        return $merchants->paginate($request->query('per_page') ?? 15);
    }

    public function show($id)
    {
        return Merchant::with(['stores', 'tags', 'activity_reasons', 'competitors'])->findOrFail($id);
    }

    public function store(StoreMerchantRequest $request, StoreMerchantUseCase $storeMerchantUseCase)
    {
        $merchant = $storeMerchantUseCase->execute(
            company_id: (int) $request->input('company_id')
        );

        return $merchant;
    }

    public function update($id, UpdateMerchantRequest $request, UpdateMerchantUseCase $updateMerchantUseCase)
    {
        $updateMerchantDTO = UpdateMerchantDTO::fromArray((int) $id, $request->validated());
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

    public function setResponsibleUser($id, SetResponsibleUserRequest $request, SetResponsibleUserUseCase $setResponsibleUserUseCase)
    {
        return $setResponsibleUserUseCase->execute($id, $request->input('maintainer_id'));
    }

    public function setMainStore($id, SetMainStoreRequest $request, SetMainStoreUseCase $setMainStoreUseCase)
    {
        return $setMainStoreUseCase->execute($id, $request->input('store_id'));
    }

    public function setTags($id, Request $request)
    {
        $this->validate($request, [
            'tags' => 'required|array',
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
                'merchant_infos.limit',
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
                'sub_query.name',
            ])->whereRaw("(IFNULL(sub_query.limit, 0) + IFNULL(sub_query.agreement_sum, 0)) $percentage_of_limit <= sub_query.current_sales")->get();
    }

    public function toggle($id, Request $request)
    {
        $this->validate($request, [
            'activity_reason_id' => 'integer|required',
        ]);

        $activity_reason = ActivityReason::where('type', 'MERCHANT')
            ->findOrFail($request->input('activity_reason_id'));

        $merchant = Merchant::findOrFail($id);
        $merchant->active = !$merchant->active;
        $merchant->save();

        $merchant->activity_reasons()->attach($activity_reason->id, [
            'active' => $merchant->active,
            'created_by_id' => $this->user->getId(),
            'created_by_name' => $this->user->getName(),
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

    public function attachCompetitor($id, CompetitorsRequest $request, FindMerchantUseCase $findMerchantUseCase)
    {
        $merchant = $findMerchantUseCase->execute($id);
        $competitor = Competitor::query()->findOrFail($request->input('competitor_id'));

        if ($merchant->competitors()->find($competitor->id)) {
            throw new ApiBusinessException('Информация о данном конкуренте на этого мерчанта уже была создана', 'merchant_competitor_exists', [
                'ru' => 'Информация о данном конкуренте на этого мерчанта уже была создана',
                'uz' => 'Merchantdagi bu konkurent haqidagi ma\'lumot qo\'shib bo\'lingan ekan',
            ], 400);
        }

        $merchant->competitors()->attach($competitor->id, [
            'volume_sales' => $request->input('volume_sales'),
            'percentage_approve' => $request->input('percentage_approve'),
            'partnership_at' => $request->input('partnership_at') !== null ? Carbon::parse($request->input('partnership_at')) : null,
        ]);

        return $merchant->load('competitors');
    }

    public function updateCompetitor($id, CompetitorsRequest $request, FindMerchantUseCase $findMerchantUseCase)
    {
        $merchant = $findMerchantUseCase->execute($id);
        $competitor = Competitor::query()->findOrFail($request->input('competitor_id'));

        $merchant->competitors()->findOrFail($competitor->id);
        $merchant->competitors()->detach($competitor->id);
        $merchant->competitors()->attach($competitor->id, [
            'volume_sales' => (int) $request->input('volume_sales'),
            'percentage_approve' => (int) $request->input('percentage_approve'),
            'partnership_at' => $request->input('partnership_at') !== null ? Carbon::parse($request->input('partnership_at')) : null,
        ]);

        return $merchant->load('competitors');
    }

    public function detachCompetitor($id, Request $request, FindMerchantUseCase $findMerchantUseCase)
    {
        $merchant = $findMerchantUseCase->execute($id);
        $competitor = Competitor::query()->findOrFail($request->input('competitor_id'));

        $merchant->competitors()->findOrFail($competitor->id);

        $merchant->competitors()->detach($competitor->id);

        return response()->json(['message' => 'Данные о конкуренте были удалены у этого мерчанта']);
    }
}
