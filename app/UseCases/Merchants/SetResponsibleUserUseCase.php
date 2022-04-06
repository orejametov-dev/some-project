<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;

use App\Exceptions\NotFoundException;
use App\HttpRepositories\Auth\AuthHttpRepository;
use App\Models\Merchant;

class SetResponsibleUserUseCase
{
    public function __construct(
        private AuthHttpRepository $authHttpRepository,
        private FindMerchantByIdUseCase $findMerchantUseCase
    ) {
    }

    public function execute(int $merchant_id, int $maintainer_id): Merchant
    {
        if ($this->authHttpRepository->checkUserToExistById($maintainer_id) === false) {
            throw new NotFoundException('Пользователь не найден');
        }

        $merchant = $this->findMerchantUseCase->execute($merchant_id);
        $merchant->maintainer_id = $maintainer_id;

        $merchant->save();

        return $merchant;
    }
}
