<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;


use App\Exceptions\BusinessException;
use App\HttpRepositories\CompanyHttpRepositories\CompanyHttpRepository;
use App\HttpServices\Company\CompanyService;
use App\Modules\Merchants\DTO\Merchants\MerchantsDTO;
use App\Modules\Merchants\Models\Merchant;
use Illuminate\Support\Facades\Cache;

class StoreMerchantUseCase
{
    public function __construct(
        private CompanyHttpRepository $companyHttpRepository)
    {
    }

    public function execute(int $company_id, int $user_id) : Merchant
    {
        $company = $this->companyHttpRepository->getCompanyById($company_id);

        if (Merchant::query()->where('company_id', $company['id'])->exists()) {
            throw new BusinessException('Указаная компания уже имеет аъзо модуль');
        }

//        $merchant = $merchantsService->create(new MerchantsDTO(
//            id: $company['id'],
//            name: $company['name'],
//            legal_name: $company['legal_name'],
//            legal_name_prefix: $company['legal_name_prefix'],
//            information: null,
//            maintainer_id: $user_id,
//            company_id: $company['id']
//        ));

        $merchant = new Merchant();
        $merchant->id = $merchantsDTO->id;
        $merchant->name = $merchantsDTO->name;
        $merchant->legal_name = $merchantsDTO->legal_name;
        $merchant->legal_name_prefix = $merchantsDTO->legal_name_prefix;
        $merchant->token = $merchantsDTO->token;
        $merchant->alifshop_slug = $merchantsDTO->alifshop_slug;
        $merchant->information = $merchantsDTO->information;
        $merchant->maintainer_id = $merchantsDTO->maintainer_id;
        $merchant->company_id = $merchantsDTO->company_id;
        $merchant->save();


        Cache::tags($merchant->id)->flush();
        Cache::tags('azo_merchants')->flush();
        Cache::tags('company')->flush();

        $this->companyHttpRepository->setStatusExist($company['id']);

        return $merchant;
    }
}
