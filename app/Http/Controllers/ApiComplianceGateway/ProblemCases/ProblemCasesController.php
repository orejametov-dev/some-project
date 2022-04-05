<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiComplianceGateway\ProblemCases;

use App\DTOs\ProblemCases\StoreProblemCaseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrmGateway\ProblemCases\AttachNewProblemCaseTagsRequest;
use App\Http\Requests\ApiPrmGateway\ProblemCases\StoreProblemCaseRequest;
use App\UseCases\ProblemCase\NewAttachTagsProblemCaseUseCase;
use App\UseCases\ProblemCase\StoreProblemCaseApplicationIdUseCase;

class ProblemCasesController extends Controller
{
    public function store(StoreProblemCaseRequest $request, StoreProblemCaseApplicationIdUseCase $storeProblemCaseUseCase)
    {
        $problemCaseDTO = StoreProblemCaseDTO::fromArray($request->validated());

        return $storeProblemCaseUseCase->execute($problemCaseDTO);
    }

    public function attachTags($id, AttachNewProblemCaseTagsRequest $request, NewAttachTagsProblemCaseUseCase $newAttachTagsProblemCaseUseCase)
    {
        return $newAttachTagsProblemCaseUseCase->execute((int) $id, (array) $request->input('tags'));
    }
}
