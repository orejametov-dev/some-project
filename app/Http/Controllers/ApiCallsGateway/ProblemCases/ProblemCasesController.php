<?php

namespace App\Http\Controllers\ApiCallsGateway\ProblemCases;

use App\DTOs\ProblemCases\ProblemCaseDTO;
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
            ->with('merchant')
            ->whereIn('created_from_name', ['CALLS', 'LAW'])
            ->filterRequests($request);

        if ($request->query('object') == true) {
            return new ProblemCaseResource($problemCases->first());
        }

        return ProblemCaseResource::collection($problemCases->paginate($request->query('per_page') ?? 15));
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
