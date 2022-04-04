<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiLawGateway\ProblemCases;

use App\DTOs\ProblemCases\StoreProblemCaseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\ProblemCases\AttachNewProblemCaseTagsRequest;
use App\Http\Requests\ApiPrm\ProblemCases\StoreProblemCaseRequest;
use App\UseCases\ProblemCase\NewAttachTagsProblemCaseUseCase;
use App\UseCases\ProblemCase\StoreProblemCaseNumberCreditUseCase;
use Illuminate\Http\Resources\Json\JsonResource;

class ProblemCasesController extends Controller
{
    public function store(StoreProblemCaseRequest $request, StoreProblemCaseNumberCreditUseCase $storeProblemCasesUseCase): JsonResource
    {
        $problemCaseDTO = StoreProblemCaseDTO::fromArray($request->validated());
        $problemCase = $storeProblemCasesUseCase->execute($problemCaseDTO);

        return new JsonResource($problemCase);
    }

    public function attachTags(int $id, AttachNewProblemCaseTagsRequest $request, NewAttachTagsProblemCaseUseCase $newAttachTagsProblemCaseUseCase): JsonResource
    {
        $problemCase = $newAttachTagsProblemCaseUseCase->execute($id, (array) $request->input('tags'));

        return new JsonResource($problemCase);
    }
}
