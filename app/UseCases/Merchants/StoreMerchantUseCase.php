<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;


use App\Exceptions\BusinessException;
use App\HttpRepositories\CompanyHttpRepositories\CompanyHttpRepository;
use App\HttpServices\Company\CompanyService;
use App\Modules\Merchants\DTO\Merchants\MerchantsDTO;
use App\Modules\Merchants\Models\Merchant;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class StoreMerchantUseCase
{
    public function __construct(
        private CompanyHttpRepository $companyHttpRepository,
        private MerchantRepository $merchantRepository,
    )
    {
    }

    public function execute(int $company_id, int $user_id) : MerchantResponse
    {
        $company = $this->companyHttpRepository->getCompanyById($company_id);

        if ($merchant->checkCompanyByIdToExists($company_id)) {
            throw new BusinessException('Указаная компания уже имеет аъзо модуль');
        }

        $merchant = new Merchant();
        $merchant->id = $company->id;
        $merchant->name = $company->name;
        $merchant->legal_name = $company->legal_name;
        $merchant->legal_name_prefix = $company->legal_name_prefix;
        $merchant->token = $company->token;
        $merchant->alifshop_slug = Str::slug($company->name);
        $merchant->maintainer_id = $user_id;
        $merchant->company_id = $company->id;
        $merchant->save();


        Cache::tags($merchant->id)->get('sdaasdsad')->flush();
        Cache::tags('azo_merchants')->flush();
        Cache::tags('company')->flush();

        $this->companyHttpRepository->setStatusExist($company->id);

        return $merchant;
    }
}
