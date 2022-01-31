<?php

namespace App\Http\Controllers\ApiLawGateway\ProblemCases;

use App\DTOs\ProblemCases\ProblemCaseDTO;
use App\Http\Controllers\ApiLawGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\Merchants\ProblemCases\ProblemCaseStoreRequest;
use App\UseCases\ProblemCase\StoreProblemCaseNumberCreditUseCase;

class ProblemCasesController extends ApiBaseController
{
    public function store(ProblemCaseStoreRequest $request, StoreProblemCaseNumberCreditUseCase $storeProblemCasesUseCase)
    {
        $problemCaseDTO = ProblemCaseDTO::fromArray($request->validated());

        return $storeProblemCasesUseCase->execute($problemCaseDTO);
    }
}
