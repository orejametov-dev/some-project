<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiComplianceGateway\ProblemCases;

use App\DTOs\ProblemCases\StoreProblemCaseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrmGateway\ProblemCases\AttachNewProblemCaseTagsRequest;
use App\Http\Requests\ApiPrmGateway\ProblemCases\StoreProblemCaseRequest;
use App\Http\Resources\ApiComplianceGateway\ProblemCases\ProblemCaseResource;
use App\Http\Resources\ApiComplianceGateway\ProblemCases\ProblemCaseWithTagsResource;
use App\UseCases\ProblemCase\NewAttachTagsProblemCaseUseCase;
use App\UseCases\ProblemCase\StoreProblemCaseApplicationIdUseCase;

class ProblemCasesController extends Controller
{
    public function store(StoreProblemCaseRequest $request, StoreProblemCaseApplicationIdUseCase $storeProblemCaseUseCase): ProblemCaseResource
    {
        $problemCaseDTO = StoreProblemCaseDTO::fromArray($request->validated());
        $problemCase = $storeProblemCaseUseCase->execute($problemCaseDTO);

        return new ProblemCaseResource($problemCase);
    }

    public function attachTags(int $id, AttachNewProblemCaseTagsRequest $request, NewAttachTagsProblemCaseUseCase $newAttachTagsProblemCaseUseCase): ProblemCaseWithTagsResource
    {
        $problemCase = $newAttachTagsProblemCaseUseCase->execute($id, (array) $request->input('tags'));

        return new ProblemCaseWithTagsResource($problemCase);
    }
}
