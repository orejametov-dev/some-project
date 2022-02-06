<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;

use App\Exceptions\BusinessException;
use App\HttpRepositories\Auth\AuthHttpRepository;
use App\Modules\Merchants\Models\Merchant;

class SetResponsibleUserUseCase
{
    public function __construct(
        private AuthHttpRepository $authHttpRepository,
        private FindMerchantUseCase $findMerchantUseCase
    ) {
    }

    public function execute(int $merchant_id, int $maintainer_id): Merchant
    {
        if ($this->authHttpRepository->checkUserToExistById($maintainer_id) === false) {
            throw new BusinessException('Пользователь не найден', 'user_not_exists', 404);
        }

        $merchant = $this->findMerchantUseCase->execute($merchant_id);
        $merchant->maintainer_id = $maintainer_id;

        $merchant->save();

        return $merchant;
    }
}
