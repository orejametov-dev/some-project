<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\Exceptions\BusinessException;
use App\HttpRepositories\Prm\CompanyHttpRepository;
use App\Modules\Merchants\Models\Merchant;
use App\UseCases\Cache\FlushCacheUseCase;

class StoreMerchantUseCase
{
    public function __construct(
        private CompanyHttpRepository $companyHttpRepository,
        private FlushCacheUseCase $flushCacheUseCase,
        private GatewayAuthUser $gatewayAuthUser
    ) {
    }

    public function execute(int $company_id): Merchant
    {
        $company = $this->companyHttpRepository->getCompanyById($company_id);

        if (Merchant::where('company_id', $company_id)->exists()) {
            throw new BusinessException('Указаная компания уже имеет аъзо модуль');
        }

        $merchant = Merchant::fromDto($company, $this->gatewayAuthUser->getId());
        $merchant->save();

        $this->flushCacheUseCase->execute($merchant->id);
        $this->companyHttpRepository->setStatusExist($company->id);

        return $merchant;
    }
}
