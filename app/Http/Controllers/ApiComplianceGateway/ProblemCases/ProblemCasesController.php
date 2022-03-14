<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiComplianceGateway\ProblemCases;

use App\DTOs\ProblemCases\ProblemCaseDTO;
use App\Http\Controllers\ApiComplianceGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\ProblemCases\ProblemCaseNewAttachTagsRequest;
use App\Http\Requests\ApiPrm\ProblemCases\ProblemCaseStoreRequest;
use App\UseCases\ProblemCase\NewAttachTagsProblemCaseUseCase;
use App\UseCases\ProblemCase\StoreProblemCaseApplicationIdUseCase;

class ProblemCasesController extends ApiBaseController
{
    public function store(ProblemCaseStoreRequest $request, StoreProblemCaseApplicationIdUseCase $storeProblemCaseUseCase)
    {
        $problemCaseDTO = ProblemCaseDTO::fromArray($request->validated());

        return $storeProblemCaseUseCase->execute($problemCaseDTO);
    }

    public function attachTags($id, ProblemCaseNewAttachTagsRequest $request, NewAttachTagsProblemCaseUseCase $newAttachTagsProblemCaseUseCase)
    {
        return $newAttachTagsProblemCaseUseCase->execute((int) $id, (array) $request->input('tags'));
    }
}
