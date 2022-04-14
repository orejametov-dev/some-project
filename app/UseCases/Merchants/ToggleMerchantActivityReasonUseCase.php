<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\Exceptions\NotFoundException;
use App\HttpRepositories\Prm\CompanyHttpRepository;
use App\Models\Merchant;
use App\Repositories\ActivityReasonRepository;
use App\Repositories\MerchantRepository;
use App\UseCases\Cache\FlushCacheUseCase;

class ToggleMerchantActivityReasonUseCase
{
    public function __construct(
        private FlushCacheUseCase $flushCacheUseCase,
        private CompanyHttpRepository $companyHttpRepository,
        private FindMerchantByIdUseCase $findMerchantByIdUseCase,
        private GatewayAuthUser $gatewayAuthUser,
        private MerchantRepository $merchantRepository,
        private ActivityReasonRepository $activityReasonRepository,
    ) {
    }

    public function execute(int $id, int $activity_reason_id): Merchant
    {
        $activity_reason = $this->activityReasonRepository->getByIdWithType('MERCHANT', $activity_reason_id);

        if ($activity_reason === null) {
            throw new NotFoundException('Причина не найдена');
        }

        $merchant = $this->findMerchantByIdUseCase->execute($id);
        $merchant->active = !$merchant->active;
        $this->merchantRepository->save($merchant);

        $this->merchantRepository->attachActivityReason($merchant, $activity_reason->id, $this->gatewayAuthUser);

        $this->companyHttpRepository->setStatusNotActive($merchant->company_id);

        $this->flushCacheUseCase->execute($merchant->id);

        return $merchant;
    }
}
