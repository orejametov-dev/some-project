<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiLawGateway\ProblemCases;

use App\DTOs\ProblemCases\ProblemCaseDTO;
use App\Http\Controllers\ApiLawGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\ProblemCases\ProblemCaseNewAttachTagsRequest;
use App\Http\Requests\ApiPrm\ProblemCases\ProblemCaseStoreRequest;
use App\UseCases\ProblemCase\NewAttachTagsProblemCaseUseCase;
use App\UseCases\ProblemCase\StoreProblemCaseNumberCreditUseCase;

class ProblemCasesController extends ApiBaseController
{
    public function store(ProblemCaseStoreRequest $request, StoreProblemCaseNumberCreditUseCase $storeProblemCasesUseCase)
    {
        $problemCaseDTO = ProblemCaseDTO::fromArray($request->validated());

        return $storeProblemCasesUseCase->execute($problemCaseDTO);
    }

    public function attachTags($id, ProblemCaseNewAttachTagsRequest $request, NewAttachTagsProblemCaseUseCase $newAttachTagsProblemCaseUseCase)
    {
        return $newAttachTagsProblemCaseUseCase->execute((int) $id, (array) $request->input('tags'));
    }
}
