<?php


namespace App\Http\Controllers\ApiCallsGateway\ProblemCases;


use App\Http\Controllers\ApiCallsGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\Merchants\ProblemCases\ProblemCaseStoreRequest;
use App\Modules\Merchants\DTO\ProblemCases\ProblemCaseDTO;
use App\Modules\Merchants\Models\ProblemCase;
use App\UseCases\ApiCallsGateway\ProblemCases\IndexProblemCasesUseCase;
use App\UseCases\ApiCallsGateway\ProblemCases\StoreProblemCasesUseCase;
use Illuminate\Http\Request;

class ProblemCasesController extends ApiBaseController
{
    public function index(Request $request, IndexProblemCasesUseCase $indexProblemCasesUseCase)
    {
        return $indexProblemCasesUseCase->execute($request);
    }

    public function store(ProblemCaseStoreRequest $request, StoreProblemCasesUseCase $storeProblemCasesUseCase)
    {
        $problemCaseDTO = new ProblemCaseDTO(
            created_from_name: (string)"CALLS",
            description: (string)$request->input('description'),
            credit_number: $request->input('credit_number')
        );

        return $storeProblemCasesUseCase->execute($problemCaseDTO, $this->user);
    }


    public function getStatusList()
    {
        return array_values(ProblemCase::$statuses);
    }

}
