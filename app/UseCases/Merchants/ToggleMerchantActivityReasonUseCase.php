<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\Exceptions\NotFoundException;
use App\HttpRepositories\Prm\CompanyHttpRepository;
use App\Models\Merchant;
use App\Models\MerchantActivity;
use App\Repositories\ActivityReasonRepository;
use App\Repositories\MerchantActivityRepository;
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
        private MerchantActivityRepository $merchantActivityRepository,
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

        $merchantActivity = new MerchantActivity();
        $merchantActivity->merchant_id = $merchant->id;
        $merchantActivity->activity_reason_id = $activity_reason->id;
        $merchantActivity->active = $merchant->active;
        $merchantActivity->created_by_id = $this->gatewayAuthUser->getId();
        $merchantActivity->created_by_name = $this->gatewayAuthUser->getName();
        $this->merchantActivityRepository->save($merchantActivity);

        $this->companyHttpRepository->setStatusNotActive($merchant->company_id);

        $this->flushCacheUseCase->execute($merchant->id);

        return $merchant;
    }
}
