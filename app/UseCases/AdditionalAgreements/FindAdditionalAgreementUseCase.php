<?php

declare(strict_types=1);

namespace App\UseCases\AdditionalAgreements;

use App\Exceptions\BusinessException;
use App\Models\AdditionalAgreement;

class FindAdditionalAgreementUseCase
{
    public function execute(int $id): AdditionalAgreement
    {
        $additional_agreement = AdditionalAgreement::query()->find($id);

        if ($additional_agreement === null) {
            throw new BusinessException('Дополнительное соглашение не найдено', 'object_not_found', 404);
        }

        return $additional_agreement;
    }
}
