<?php


namespace App\Http\Controllers\ApiGateway\Companies;


use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Modules\AlifshopMerchants\DTO\AlifshopMerchantDTO;
use App\Modules\AlifshopMerchants\Services\AlifshopMerchantService;
use App\Modules\Companies\DTO\CompanyDTO;
use App\Modules\Companies\Models\Company;
use App\Modules\Companies\Services\CompanyService;
use App\Modules\Merchants\DTO\Merchants\MerchantsDTO;
use App\Modules\Merchants\Services\Merchants\MerchantsService;
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

    public function storeSpecial(
        Request $request,
        CompanyService $companyService,
        MerchantsService $merchantsService,
        AlifshopMerchantService $alifshopMerchantService
    )
    {
        $this->validate($request, [
            'name' => 'required|string',
            'legal_name' => 'required|string',
            'merchant_type' => 'required|string|in:azo_merchant,alifshop_merchant',
            'tags' => 'required|array'
        ]);

        $company_name_exists = Company::query()->where('name', $request->input('name'))->exists();
        if ($company_name_exists) {
            return response()->json(['message' => 'Указанное имя компании уже занято'], 400);
        }

        $company = $companyService->create(new CompanyDTO(
            name: $request->input('name'),
            legal_name: $request->input('legal_name')
        ));


        if($request->input('merchant_type') == 'azo_merchant'){
            $merchant = $merchantsService->create(new MerchantsDTO(
                id: $company->id,
                name: $company->name,
                legal_name: $company->legal_name,
                information: null,
                maintainer_id: $this->user->id,
                company_id: $company->id
            ));

            $merchant->tags()->attach($request->input('tags'));
        }

        if($request->input('merchant_type') == 'alifshop_merchant'){
            $alifshop_merchant = $alifshopMerchantService->create(new AlifshopMerchantDTO(
                id: $company->id,
                name: $company->name,
                legal_name: $company->legal_name,
                information: null,
                maintainer_id: $this->user->id,
                company_id: $company->id
            ));

            $alifshop_merchant->tags()->attach($request->input('tags'));
        }

        return $company;
    }
}
