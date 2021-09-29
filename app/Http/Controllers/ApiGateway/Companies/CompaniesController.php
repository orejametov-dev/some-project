<?php


namespace App\Http\Controllers\ApiGateway\Companies;


use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Modules\Companies\DTO\CompanyDTO;
use App\Modules\Companies\Models\Company;
use App\Modules\Companies\Services\CompanyService;
use Illuminate\Http\Request;

class CompaniesController extends ApiBaseController
{
    public function index(Request $request)
    {
        $companies = Company::query()
            ->filterRequest($request);

        return $companies->paginate($request->query('per_page') ?? 15);
    }

    public function store(Request $request, CompanyService $companyService)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'legal_name' => 'required|string'
        ]);

        $company = $companyService->create(new CompanyDTO(
            name: $request->input('name'),
            legal_name: $request->input('legal_name')
        ));

        return $company;
    }
}
