<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiCallsGateway\ProblemCases;

use App\DTOs\ProblemCases\ProblemCaseDTO;
use App\Exceptions\BusinessException;
use App\Filters\CommonFilters\ClientIdFilter;
use App\Filters\CommonFilters\StatusIdFilter;
use App\Filters\ProblemCase\QProblemCaseFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\ProblemCases\ProblemCaseStoreRequest;
use App\Http\Resources\ApiCallsGateway\ProblemCases\ProblemCaseResource;
use App\Models\ProblemCase;
use App\UseCases\ProblemCase\StoreProblemCaseNumberCreditUseCase;
use Illuminate\Http\Request;

class ProblemCasesController extends Controller
{
    public function index(Request $request)
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

    public function show($id)
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

    public function store(ProblemCaseStoreRequest $request, StoreProblemCaseNumberCreditUseCase $storeProblemCasesUseCase)
    {
        $problemCaseDTO = ProblemCaseDTO::fromArray($request->validated());

        return $storeProblemCasesUseCase->execute($problemCaseDTO);
    }

    public function getStatusList()
    {
        return array_values(ProblemCase::$statuses);
    }
}
