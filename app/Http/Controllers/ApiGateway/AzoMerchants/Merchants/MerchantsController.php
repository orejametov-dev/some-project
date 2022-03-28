<?php

//declare(strict_types=1);

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\DTOs\Competitors\CompetitorDTO;
use App\DTOs\Merchants\UpdateMerchantDTO;
use App\Filters\CommonFilters\ActiveFilter;
use App\Filters\CommonFilters\TagsFilter;
use App\Filters\Merchant\ActivityReasonIdFilter;
use App\Filters\Merchant\MaintainerIdFilter;
use App\Filters\Merchant\QMerchantFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\Competitors\CompetitorsRequest;
use App\Http\Requests\ApiPrm\Files\StoreFileRequest;
use App\Http\Requests\ApiPrm\Merchants\SetMainStoreRequest;
use App\Http\Requests\ApiPrm\Merchants\SetResponsibleUserRequest;
use App\Http\Requests\ApiPrm\Merchants\StoreMerchantRequest;
use App\Http\Requests\ApiPrm\Merchants\UpdateMerchantRequest;
use App\Models\Merchant;
use App\UseCases\Competitors\AttachCompetitorUseCase;
use App\UseCases\Competitors\DetachCompetitorUseCase;
use App\UseCases\Competitors\UpdateCompetitorUseCase;
use App\UseCases\Merchants\DeleteMerchantLogoUseCase;
use App\UseCases\Merchants\FindMerchantByIdUseCase;
use App\UseCases\Merchants\SetMerchantMainStoreUseCase;
use App\UseCases\Merchants\SetMerchantTagsUseCase;
use App\UseCases\Merchants\SetResponsibleUserUseCase;
use App\UseCases\Merchants\StoreMerchantUseCase;
use App\UseCases\Merchants\ToggleMerchantActivityReasonUseCase;
use App\UseCases\Merchants\ToggleMerchantGeneralGoodsUseCase;
use App\UseCases\Merchants\ToggleMerchantRecommendUseCase;
use App\UseCases\Merchants\UpdateMerchantUseCase;
use App\UseCases\Merchants\UploadMerchantLogoUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MerchantsController extends Controller
{
    public function index(Request $request)
    {
        $merchants = Merchant::query()
            ->with(['tags'])
            ->filterRequest($request, [
                QMerchantFilter::class,
                ActiveFilter::class,
                MaintainerIdFilter::class,
                TagsFilter::class,
                ActivityReasonIdFilter::class,
            ])
            ->orderRequest($request);

        if ($request->query('object') == 'true') {
            return $merchants->first();
        }

        return $merchants->paginate($request->query('per_page') ?? 15);
    }

    public function show($id, FindMerchantByIdUseCase $findMerchantByIdUseCase)
    {
        $merchant = $findMerchantByIdUseCase->execute((int) $id);
        $merchant->load(['stores', 'tags', 'activity_reasons', 'competitors']);

        return $merchant;
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

    public function uploadLogo($id, StoreFileRequest $request, UploadMerchantLogoUseCase $uploadMerchantLogoUseCase)
    {
        return $uploadMerchantLogoUseCase->execute((int) $id, $request->file('file'));
    }

    public function removeLogo($id, DeleteMerchantLogoUseCase $deleteMerchantLogoUseCase)
    {
        $deleteMerchantLogoUseCase->execute((int) $id);

        return response()->json(['message' => 'Логотип удалён']);
    }

    public function setResponsibleUser($id, SetResponsibleUserRequest $request, SetResponsibleUserUseCase $setResponsibleUserUseCase)
    {
        return $setResponsibleUserUseCase->execute($id, $request->input('maintainer_id'));
    }

    public function setMainStore($id, SetMainStoreRequest $request, SetMerchantMainStoreUseCase $setMainStoreUseCase)
    {
        return $setMainStoreUseCase->execute($id, $request->input('store_id'));
    }

    public function setTags($id, Request $request, SetMerchantTagsUseCase $setMerchantTagsUseCase)
    {
        $this->validate($request, [
            'tags' => 'required|array',
        ]);

        return $setMerchantTagsUseCase->execute((int) $id, $request->input('tags'));
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

    public function toggle($id, Request $request, ToggleMerchantActivityReasonUseCase $toggleMerchantActivityReasonUseCase)
    {
        $this->validate($request, [
            'activity_reason_id' => 'integer|required',
        ]);

        return $toggleMerchantActivityReasonUseCase->execute((int) $id, (int) $request->input('activity_reason_id'));
    }

    public function toggleGeneralGoods($id, ToggleMerchantGeneralGoodsUseCase $toggleMerchantGeneralGoodsUseCase)
    {
        return $toggleMerchantGeneralGoodsUseCase->execute((int) $id);
    }

    public function toggleRecommend($id, ToggleMerchantRecommendUseCase $toggleMerchantRecommendUseCase)
    {
        return $toggleMerchantRecommendUseCase->execute((int) $id);
    }

    public function attachCompetitor($id, CompetitorsRequest $request, AttachCompetitorUseCase $attachCompetitorUseCase)
    {
        $competitorDTO = CompetitorDTO::fromArray((int) $id, $request->validated());
        $response = $attachCompetitorUseCase->execute($competitorDTO);

        return $response;
    }

    public function updateCompetitor($id, CompetitorsRequest $request, UpdateCompetitorUseCase $updateCompetitorUseCase)
    {
        $competitorDTO = CompetitorDTO::fromArray((int) $id, $request->validated());
        $response = $updateCompetitorUseCase->execute($competitorDTO);

        return $response;
    }

    public function detachCompetitor($id, Request $request, DetachCompetitorUseCase $detachCompetitorUseCase)
    {
        $detachCompetitorUseCase->execute((int) $id, (int) $request->input('competitor_id'));

        return response()->json(['message' => 'Данные о конкуренте были удалены у этого мерчанта']);
    }
}
