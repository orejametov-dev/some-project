<?php

namespace App\Http\Controllers\ApiCallsGateway\ProblemCases;

use App\DTOs\ProblemCases\ProblemCaseDTO;
use App\Exceptions\BusinessException;
use App\Http\Controllers\ApiCallsGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\ProblemCases\ProblemCaseStoreRequest;
use App\Http\Resources\ApiCallsGateway\ProblemCases\ProblemCaseResource;
use App\Modules\Merchants\Models\ProblemCase;
use App\UseCases\ProblemCase\StoreProblemCaseNumberCreditUseCase;
use Illuminate\Http\Request;

class ProblemCasesController extends ApiBaseController
{
    public function index(Request $request)
    {
        $problemCases = ProblemCase::query()
            ->with(['merchant', 'before_tags'])
            ->whereIn('created_from_name', ['CALLS', 'LAW'])
            ->filterRequests($request)
            ->orderByDesc('id');

        if ($request->query('object') == true) {
            return new ProblemCaseResource($problemCases->first());
        }

        return ProblemCaseResource::collection($problemCases->paginate($request->query('per_page') ?? 15));
    }

    public function show($id)
    {
        $problemCases = ProblemCase::query()
            ->with('merchant')
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
