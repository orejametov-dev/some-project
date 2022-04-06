<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiCallsGateway\ProblemCases;

use App\DTOs\ProblemCases\StoreProblemCaseDTO;
use App\Exceptions\BusinessException;
use App\Filters\CommonFilters\ClientIdFilter;
use App\Filters\CommonFilters\StatusIdFilter;
use App\Filters\ProblemCase\QProblemCaseFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrmGateway\ProblemCases\AttachNewProblemCaseTagsRequest;
use App\Http\Requests\ApiPrmGateway\ProblemCases\StoreProblemCaseRequest;
use App\Http\Resources\ApiCallsGateway\ProblemCases\ProblemCaseResource;
use App\Mappings\ProblemCaseStatusMapping;
use App\Models\ProblemCase;
use App\UseCases\ProblemCase\NewAttachTagsProblemCaseUseCase;
use App\UseCases\ProblemCase\StoreProblemCaseNumberCreditUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProblemCasesController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $problemCases = ProblemCase::query()
            ->with(['merchant', 'before_tags'])
            ->whereIn('created_from_name', ['CALLS', 'LAW'])
            ->filterRequest($request, [
                QProblemCaseFilter::class,
                StatusIdFilter::class,
                ClientIdFilter::class,
            ]);

        return ProblemCaseResource::collection($problemCases->paginate($request->query('per_page') ?? 15));
    }

    public function show(int $id): ProblemCaseResource
    {
        $problemCases = ProblemCase::query()
            ->with(['merchant', 'before_tags'])
            ->whereIn('created_from_name', ['CALLS', 'LAW'])
            ->find($id);

        if ($problemCases === null) {
            throw new BusinessException('Проблем кейс не найден', 'object_not_found', 404);
        }

        return new ProblemCaseResource($problemCases);
    }

    public function store(StoreProblemCaseRequest $request, StoreProblemCaseNumberCreditUseCase $storeProblemCasesUseCase): ProblemCaseResource
    {
        $problemCaseDTO = StoreProblemCaseDTO::fromArray($request->validated());
        $problemCase = $storeProblemCasesUseCase->execute($problemCaseDTO);

        return new ProblemCaseResource($problemCase);
    }

    public function attachTags(int $id, AttachNewProblemCaseTagsRequest $request, NewAttachTagsProblemCaseUseCase $newAttachTagsProblemCaseUseCase): ProblemCaseResource
    {
        $problemCase = $newAttachTagsProblemCaseUseCase->execute($id, (array) $request->input('tags'));

        return new ProblemCaseResource($problemCase);
    }

    public function getStatusList(ProblemCaseStatusMapping $problemCaseStatusMapping): array
    {
        return $problemCaseStatusMapping->getMappings();
    }
}
