<?php


namespace App\Modules\Companies\Services;


use App\Modules\Companies\DTO\CompanyDTO;
use App\Modules\Companies\Models\Company;

class CompanyService
{
    public function create(CompanyDTO $companyDTO)
    {
        $company = new Company();
        $company->name = $companyDTO->name;
        $company->legal_name = $companyDTO->legal_name;
        $company->legal_name_prefix = $companyDTO->legal_name_prefix;
        $company->save();

        return $company;
    }

}
