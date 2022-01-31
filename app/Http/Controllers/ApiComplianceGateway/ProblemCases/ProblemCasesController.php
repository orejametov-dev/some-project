<?php

namespace App\Http\Controllers\ApiComplianceGateway\ProblemCases;

use App\DTOs\ProblemCases\ProblemCaseDTO;
use App\Http\Controllers\ApiComplianceGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\Merchants\ProblemCases\ProblemCaseStoreRequest;
use App\UseCases\ProblemCase\StoreProblemCaseApplicationIdUseCase;

class ProblemCasesController extends ApiBaseController
{
    public function store(ProblemCaseStoreRequest $request , StoreProblemCaseApplicationIdUseCase $storeProblemCaseUseCase)
    {
        $problemCaseDTO = new ProblemCaseDTO(
            created_from_name: "COMPLIANCE",
            description: (string) $request->input('description'),
            identifier: (int) $request->input('application_id'),
            user_id: (int) $this->user->id,
            user_name: (string) $this->user->name,
        );

        return $storeProblemCaseUseCase->execute($problemCaseDTO);
    }

}
