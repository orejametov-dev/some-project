<?php

//declare(strict_types=1);

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\DTOs\Competitors\SaveCompetitorDTO;
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
use App\UseCases\Merchants\ToggleHoldingInitialPaymentUseCase;
use App\UseCases\Merchants\ToggleMerchantActivityReasonUseCase;
use App\UseCases\Merchants\ToggleMerchantGeneralGoodsUseCase;
use App\UseCases\Merchants\ToggleMerchantRecommendUseCase;
use App\UseCases\Merchants\UpdateMerchantUseCase;
use App\UseCases\Merchants\UploadMerchantLogoUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class MerchantsController extends Controller
{
    public function index(Request $request): JsonResource
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
            return new JsonResource($merchants->first());
        }

        return JsonResource::collection($merchants->paginate($request->query('per_page') ?? 15));
    }

    public function show(int $id, FindMerchantByIdUseCase $findMerchantByIdUseCase): JsonResource
    {
        $merchant = $findMerchantByIdUseCase->execute($id);
        $merchant->load(['stores', 'tags', 'activity_reasons', 'competitors']);

        return new JsonResource($merchant);
    }

    public function store(StoreMerchantRequest $request, StoreMerchantUseCase $storeMerchantUseCase): JsonResource
    {
        $merchant = $storeMerchantUseCase->execute(
            company_id: (int) $request->input('company_id')
        );

        return new JsonResource($merchant);
    }

    public function update($id, UpdateMerchantRequest $request, UpdateMerchantUseCase $updateMerchantUseCase): JsonResource
    {
        $merchant = $updateMerchantUseCase->execute((int) $id, UpdateMerchantDTO::fromArray($request->validated()));

        return new JsonResource($merchant);
    }

    public function uploadLogo($id, StoreFileRequest $request, UploadMerchantLogoUseCase $uploadMerchantLogoUseCase): JsonResource
    {
        $merchant = $uploadMerchantLogoUseCase->execute((int) $id, $request->file('file'));

        return new JsonResource($merchant);
    }

    public function removeLogo(int $id, DeleteMerchantLogoUseCase $deleteMerchantLogoUseCase): JsonResponse
    {
        $deleteMerchantLogoUseCase->execute($id);

        return new JsonResponse(['message' => 'Логотип удалён']);
    }

    public function setResponsibleUser(int $id, SetResponsibleUserRequest $request, SetResponsibleUserUseCase $setResponsibleUserUseCase): JsonResource
    {
        $merchant = $setResponsibleUserUseCase->execute($id, $request->input('maintainer_id'));

        return new JsonResource($merchant);
    }

    public function setMainStore(int $id, SetMainStoreRequest $request, SetMerchantMainStoreUseCase $setMainStoreUseCase): JsonResource
    {
        $merchant = $setMainStoreUseCase->execute($id, $request->input('store_id'));

        return new JsonResource($merchant);
    }

    public function setTags(int $id, Request $request, SetMerchantTagsUseCase $setMerchantTagsUseCase): JsonResource
    {
        $this->validate($request, [
            'tags' => 'required|array',
        ]);

        $merchant = $setMerchantTagsUseCase->execute($id, $request->input('tags'));

        return new JsonResource($merchant);
    }

    public function hotMerchants(): JsonResponse
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

        return new JsonResponse(DB::table(DB::raw("({$merchant_query->toSql()}) as sub_query"))
            ->select([
                'sub_query.id',
                'sub_query.name',
            ])->whereRaw("(IFNULL(sub_query.limit, 0) + IFNULL(sub_query.agreement_sum, 0)) $percentage_of_limit <= sub_query.current_sales")->get());
    }

    public function toggle(int $id, Request $request, ToggleMerchantActivityReasonUseCase $toggleMerchantActivityReasonUseCase): JsonResource
    {
        $this->validate($request, [
            'activity_reason_id' => 'integer|required',
        ]);

        $merchant = $toggleMerchantActivityReasonUseCase->execute($id, (int) $request->input('activity_reason_id'));

        return new JsonResource($merchant);
    }

    public function toggleGeneralGoods(int $id, ToggleMerchantGeneralGoodsUseCase $toggleMerchantGeneralGoodsUseCase): JsonResource
    {
        $merchant = $toggleMerchantGeneralGoodsUseCase->execute($id);

        return new JsonResource($merchant);
    }

    public function toggleRecommend(int $id, ToggleMerchantRecommendUseCase $toggleMerchantRecommendUseCase): JsonResource
    {
        $merchant = $toggleMerchantRecommendUseCase->execute($id);

        return new JsonResource($merchant);
    }

    public function toggleHoldingInitialPayment(int $id, ToggleHoldingInitialPaymentUseCase $holdingInitialPaymentUseCase): JsonResource
    {
        return new JsonResource($holdingInitialPaymentUseCase->execute($id));
    }

    public function attachCompetitor(int $id, CompetitorsRequest $request, AttachCompetitorUseCase $attachCompetitorUseCase): JsonResource
    {
        $response = $attachCompetitorUseCase->execute($id, SaveCompetitorDTO::fromArray($request->validated()));

        return new JsonResource($response);
    }

    public function updateCompetitor(int $id, CompetitorsRequest $request, UpdateCompetitorUseCase $updateCompetitorUseCase): JsonResource
    {
        $response = $updateCompetitorUseCase->execute($id, SaveCompetitorDTO::fromArray($request->validated()));

        return new JsonResource($response);
    }

    public function detachCompetitor(int $id, Request $request, DetachCompetitorUseCase $detachCompetitorUseCase): JsonResponse
    {
        $detachCompetitorUseCase->execute($id, (int) $request->input('competitor_id'));

        return new JsonResponse(['message' => 'Данные о конкуренте были удалены у этого мерчанта']);
    }
}
