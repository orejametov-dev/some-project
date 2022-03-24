<?php

declare(strict_types=1);

namespace App\UseCases\AdditionalAgreements;

use App\DTOs\AdditionalAgreements\StoreAdditionalAgreementDTO;
use App\Exceptions\BusinessException;
use App\Models\AdditionalAgreement;
use App\Models\MerchantInfo;
use App\UseCases\Merchants\FindMerchantByIdUseCase;

class StoreAdditionalAgreementUseCase
{
    public function __construct(
        private FindMerchantByIdUseCase $findMerchantByIdUseCase
    ) {
    }

    public function execute(StoreAdditionalAgreementDTO $additionalAgreementDTO): AdditionalAgreement
    {
        $merchant = $this->findMerchantByIdUseCase->execute($additionalAgreementDTO->getMerchantId());

        if (MerchantInfo::query()->where('merchant_id', $merchant->id)->exists() === false) {
            throw new BusinessException('Нет основного договора');
        }

        if ($additionalAgreementDTO->getDocumentType() === AdditionalAgreement::LIMIT && $additionalAgreementDTO->getLimit() === null) {
            throw new BusinessException('Лимит должен быть передан', 'params_not_exists', 400);
        }

        $additional_agreement = new AdditionalAgreement();
        $additional_agreement->registration_date = $additionalAgreementDTO->getRegistrationDate();
        $additional_agreement->number = $additionalAgreementDTO->getNumber();
        $additional_agreement->document_type = $additionalAgreementDTO->getDocumentType();
        $additional_agreement->limit = $additionalAgreementDTO->getLimit();
        $additional_agreement->merchant_id = $merchant->id;
        $additional_agreement->save();

        return $additional_agreement;
    }
}
