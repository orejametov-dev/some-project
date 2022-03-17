<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiLawGateway\ProblemCases;

use App\DTOs\ProblemCases\ProblemCaseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\ProblemCases\ProblemCaseStoreRequest;
use App\UseCases\ProblemCase\StoreProblemCaseNumberCreditUseCase;

class ProblemCasesController extends Controller
{
    public function store(ProblemCaseStoreRequest $request, StoreProblemCaseNumberCreditUseCase $storeProblemCasesUseCase)
    {
        $problemCaseDTO = ProblemCaseDTO::fromArray($request->validated());

        return $storeProblemCasesUseCase->execute($problemCaseDTO);
    }
}
