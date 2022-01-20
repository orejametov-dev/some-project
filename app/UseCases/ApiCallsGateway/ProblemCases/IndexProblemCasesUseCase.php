<?php

namespace App\UseCases\ApiCallsGateway\ProblemCases;

use App\Http\Resources\ApiCallsGateway\ProblemCases\ProblemCaseResource;
use App\Modules\Merchants\Models\ProblemCase;

class IndexProblemCasesUseCase
{
    public function execute($request)
    {
        $problemCases = ProblemCase::query()->filterRequests($request);

        return ProblemCaseResource::collection($problemCases->paginate($request->query('per_page') ?? 15));
    }
}
