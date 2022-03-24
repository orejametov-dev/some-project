<?php

declare(strict_types=1);

namespace App\UseCases\AdditionalAgreements;

use App\DTOs\AdditionalAgreements\StoreAdditionalAgreementDTO;
use App\Models\AdditionalAgreement;

class UpdateAdditionalAgreementUseCase
{
    public function __construct(
        private FindAdditionalAgreementUseCase $findAdditionalAgreementUseCase
    ) {
    }

    public function execute(int $id, StoreAdditionalAgreementDTO $additionalAgreementDTO): AdditionalAgreement
    {
        $additional_agreement = $this->findAdditionalAgreementUseCase->execute($id);

        $additional_agreement->registration_date = $additionalAgreementDTO->getRegistrationDate();
        $additional_agreement->number = $additionalAgreementDTO->getNumber();
        $additional_agreement->document_type = $additionalAgreementDTO->getDocumentType();
        $additional_agreement->limit = $additionalAgreementDTO->getLimit();
//        $additional_agreement->merchant_id = $additionalAgreementDTO->getMerchantId();
        $additional_agreement->save();

        return $additional_agreement;
    }
}
