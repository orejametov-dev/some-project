<?php

declare(strict_types=1);

namespace App\UseCases\AdditionalAgreements;

use App\Exceptions\NotFoundException;
use App\Models\AdditionalAgreement;

class FindAdditionalAgreementUseCase
{
    public function execute(int $id): AdditionalAgreement
    {
        $additional_agreement = AdditionalAgreement::query()->find($id);

        if ($additional_agreement === null) {
            throw new NotFoundException('Дополнительное соглашение не найдено');
        }

        return $additional_agreement;
    }
}
