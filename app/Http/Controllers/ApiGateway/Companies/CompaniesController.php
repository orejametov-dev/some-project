<?php


namespace App\Http\Controllers\ApiGateway\Companies;


use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Modules\Companies\DTO\CompanyDTO;
use App\Modules\Companies\Models\Company;
use App\Modules\Companies\Models\Module;
use App\Modules\Companies\Services\CompanyService;
use App\Modules\Merchants\DTO\Merchants\MerchantsDTO;
use App\Modules\Merchants\Models\Tag;
use App\Modules\Merchants\Services\Merchants\MerchantsService;
use Illuminate\Http\Request;

class CompaniesController extends ApiBaseController
{
    public function index(Request $request)
    {
        $companies = Company::query()
            ->with(['modules'])
            ->filterRequest($request)
            ->orderByDesc('id');

        return $companies->paginate($request->query('per_page') ?? 15);
    }

    public function show($id)
    {
        return Company::query()->with('modules')->findOrFail($id);
    }

    public function store(Request $request, CompanyService $companyService)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'legal_name' => 'required|string',
            'legal_name_prefix' => 'required|string'
        ]);

        $company = $companyService->create(new CompanyDTO(
            name: $request->input('name'),
            legal_name: $request->input('legal_name'),
            legal_name_prefix: $request->input('legal_name_prefix')
        ));

        return $company;
    }

    public function storeSpecial(
        Request $request,
        CompanyService $companyService,
        MerchantsService $merchantsService
    )
    {
        $this->validate($request, [
            'name' => 'required|string',
            'legal_name' => 'required|string',
            'legal_name_prefix' => 'required|string',
            'merchant_type' => 'required|array',
            'tags' => 'required|array'
        ]);

        $tags = Tag::whereIn('id', $request->input('tags'))->get();

        foreach ($request->input('tags') as $tag) {
            if(!$tags->contains('id', $tag)){
                return response()->json(['message' => 'Указан не правильный тег'], 400);
            }
        }

        $company_name_exists = Company::query()->where('name', $request->input('name'))->exists();
        if ($company_name_exists) {
            return response()->json(['message' => 'Указанное имя компании уже занято'], 400);
        }

         $company = \DB::transaction(function () use ($companyService, $merchantsService, $request) {
            $company = $companyService->create(new CompanyDTO(
                name: $request->input('name'),
                legal_name: $request->input('legal_name'),
                legal_name_prefix: $request->input('legal_name_prefix')
            ));

            if(in_array( 'azo_merchant', $request->input('merchant_type'))){
                $merchant = $merchantsService->create(new MerchantsDTO(
                    id: $company->id,
                    name: $company->name,
                    legal_name: $company->legal_name,
                    legal_name_prefix: $company->legal_name_prefix,
                    information: null,
                    maintainer_id: $this->user->id,
                    company_id: $company->id
                ));
                $company->modules()->attach([Module::AZO_MERCHANT]);
                $merchant->tags()->attach($request->input('tags'));
            }

            return $company;

        });


        return $company;
    }

    public function detachModule($id, Request $request)
    {
        $company = Company::query()->findOrFail($id);

        if($request->input('merchant_type') == 'alifshop_merchant'){
            $company->modules()->detach(Module::ALIFSHOP_MERCHANT);
        }

        if($request->input('merchant_type') == 'azo_merchant'){
            $company->modules()->detach(Module::AZO_MERCHANT);
        }

        return $company;
    }
}
