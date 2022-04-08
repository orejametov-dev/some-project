<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\Exceptions\NotFoundException;
use App\HttpRepositories\Prm\CompanyHttpRepository;
use App\Models\ActivityReason;
use App\Models\Merchant;
use App\UseCases\Cache\FlushCacheUseCase;

class ToggleMerchantActivityReasonUseCase
{
    public function __construct(
        private FlushCacheUseCase $flushCacheUseCase,
        private CompanyHttpRepository $companyHttpRepository,
        private FindMerchantByIdUseCase $findMerchantByIdUseCase,
        private GatewayAuthUser $gatewayAuthUser
    ) {
    }

    public function execute(int $id, int $activity_reason_id): Merchant
    {
        $activity_reason = ActivityReason::query()->where('type', 'MERCHANT')
            ->find($activity_reason_id);
        if ($activity_reason === null) {
            throw new NotFoundException('Причина не найдена');
        }

        $merchant = $this->findMerchantByIdUseCase->execute($id);
        $merchant->active = !$merchant->active;
        $merchant->save();

        $merchant->activity_reasons()->attach($activity_reason->id, [
            'active' => $merchant->active,
            'created_by_id' => $this->gatewayAuthUser->getId(),
            'created_by_name' => $this->gatewayAuthUser->getName(),
        ]);

        $this->companyHttpRepository->setStatusNotActive($merchant->company_id);

        $this->flushCacheUseCase->execute($merchant->id);

        return $merchant;
    }
}
