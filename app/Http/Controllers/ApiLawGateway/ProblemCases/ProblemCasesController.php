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
        $problemCaseDTO = new ProblemCaseDTO(
            created_from_name: "LAW",
            description: (string) $request->input('description'),
            identifier: (string) $request->input('credit_number'),
            user_id: $this->user->id,
            user_name: $this->user->name
        );

        return $storeProblemCasesUseCase->execute($problemCaseDTO);
    }
}
