<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiMerchantGateway\ProblemCases;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\DTOs\Auth\AzoAccessDto;
use App\Exceptions\BusinessException;
use App\Filters\CommonFilters\StatusIdFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiMerchantsGateway\ProblemCases\ProblemCaseSetStatusRequest;
use App\Http\Resources\ApiMerchantGateway\ProblemCases\ProblemCaseResource;
use App\Mappings\ProblemCaseStatusMapping;
use App\Models\ProblemCase;
use App\UseCases\ProblemCase\SetStatusProblemCaseUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

class ProblemCasesController extends Controller
{
    public function index(Request $request, AzoAccessDto $azoAccessDto): JsonResource
    {
        $problemCases = ProblemCase::query()->with('before_tags')
            ->byMerchant($azoAccessDto->getMerchantId())
            ->filterRequest($request, [
                StatusIdFilter::class,
            ]);

        if ($request->query('object') == true) {
            $problemCases->first();
        }

        return ProblemCaseResource::collection($problemCases->paginate($request->query('per_page') ?? 15));
    }

    public function show(int $id): ProblemCaseResource
    {
        $problemCase = ProblemCase::with('before_tags')
            ->find((int) $id);

        if ($problemCase === null) {
            throw new BusinessException('Проблемный кейс не найден', 'object_not_found', 404);
        }

        return new ProblemCaseResource($problemCase);
    }

    public function setCommentFromMerchant(int $id, Request $request): ProblemCaseResource
    {
        $this->validate($request, [
            'body' => 'string|required',
        ]);

        $problemCase = ProblemCase::query()->findOrFail((int) $id);
        $problemCase->comment_from_merchant = $request->input('body');
        $problemCase->save();

        return new ProblemCaseResource($problemCase);
    }

    public function setStatus(int $id, ProblemCaseSetStatusRequest $request, SetStatusProblemCaseUseCase $setStatusProblemCaseUseCase, ProblemCaseStatusMapping $problemCaseStatusMapping): ProblemCaseResource
    {
        $problemCase = $setStatusProblemCaseUseCase->execute($id, (int) $request->input('status_id'));

        return new ProblemCaseResource($problemCase);
    }

    public function setEngage(int $id, GatewayAuthUser $gatewayAuthUser): ProblemCaseResource
    {
        $problemCase = ProblemCase::query()->findOrFail((int) $id);

        $problemCase->engaged_by_id = $gatewayAuthUser->getId();
        $problemCase->engaged_by_name = $gatewayAuthUser->getName();
        $problemCase->engaged_at = now();

        $problemCase->save();

        return new ProblemCaseResource($problemCase);
    }

    public function getStatuses(ProblemCaseStatusMapping $problemCaseStatusMapping): array
    {
        return $problemCaseStatusMapping->getMappings();
    }

    public function getNewProblemCasesCounter(GatewayAuthUser $gatewayAuthUser, AzoAccessDto $azoAccessDto): JsonResponse
    {
        $counter = Cache::remember('new-problem-cases-counter_' . $azoAccessDto->getMerchantId(), 10 * 60, function () use ($azoAccessDto) {
            return ProblemCase::query()
                ->whereNull('engaged_by_id')
                ->whereNull('engaged_by_name')
                ->byMerchant($azoAccessDto->getMerchantId())
                ->onlyNew()
                ->count();
        });

        return new JsonResponse(['count' => (int) $counter]);
    }
}
