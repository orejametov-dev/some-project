<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiLawGateway\ProblemCases;

use App\DTOs\ProblemCases\StoreProblemCaseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\ProblemCases\AttachNewProblemCaseTagsRequest;
use App\Http\Requests\ApiPrm\ProblemCases\StoreProblemCaseRequest;
use App\UseCases\ProblemCase\NewAttachTagsProblemCaseUseCase;
use App\UseCases\ProblemCase\StoreProblemCaseNumberCreditUseCase;

class ProblemCasesController extends Controller
{
    public function store(StoreProblemCaseRequest $request, StoreProblemCaseNumberCreditUseCase $storeProblemCasesUseCase)
    {
        $problemCaseDTO = StoreProblemCaseDTO::fromArray($request->validated());

        return $storeProblemCasesUseCase->execute($problemCaseDTO);
    }

    public function attachTags(int $id, AttachNewProblemCaseTagsRequest $request, NewAttachTagsProblemCaseUseCase $newAttachTagsProblemCaseUseCase)
    {
        return $newAttachTagsProblemCaseUseCase->execute($id, (array) $request->input('tags'));
    }
}
