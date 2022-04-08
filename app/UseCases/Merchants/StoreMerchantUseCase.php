<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\Exceptions\BusinessException;
use App\HttpRepositories\Prm\CompanyHttpRepository;
use App\Models\Merchant;
use App\Repositories\MerchantRepository;
use App\UseCases\Cache\FlushCacheUseCase;

class StoreMerchantUseCase
{
    public function __construct(
        private MerchantRepository $merchantRepository,
        private CompanyHttpRepository $companyHttpRepository,
        private FlushCacheUseCase $flushCacheUseCase,
        private GatewayAuthUser $gatewayAuthUser
    ) {
    }

    public function execute(int $company_id): Merchant
    {
        $company = $this->companyHttpRepository->getCompanyById($company_id);

        if ($this->merchantRepository->existsByCompanyId($company_id)) {
            throw new BusinessException('Указаная компания уже имеет аъзо модуль');
        }

        $merchant = Merchant::fromDto($company, $this->gatewayAuthUser->getId());
        $this->merchantRepository->save($merchant);

        $this->flushCacheUseCase->execute($merchant->id);
        $this->companyHttpRepository->setStatusExist($company->id);

        return $merchant;
    }
}
