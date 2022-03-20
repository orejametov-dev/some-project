<?php

declare(strict_types=1);

namespace App\UseCases\AdditionalAgreements;

class DeleteAdditionalAgreementUseCase
{
    public function __construct(
        private FindAdditionalAgreementUseCase $findAdditionalAgreementUseCase
    ) {
    }

    public function execute(int $id): void
    {
        $additional_agreement = $this->findAdditionalAgreementUseCase->execute($id);
        $additional_agreement->delete();
    }
}
