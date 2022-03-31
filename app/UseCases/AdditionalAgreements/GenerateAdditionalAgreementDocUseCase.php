<?php

declare(strict_types=1);

namespace App\UseCases\AdditionalAgreements;

use App\Enums\AdditionalAgreementDocumentTypeEnum;
use App\Exceptions\BusinessException;
use App\Models\MerchantInfo;
use App\Services\WordService;

class GenerateAdditionalAgreementDocUseCase
{
    public function __construct(
        private FindAdditionalAgreementUseCase $findAdditionalAgreementUseCase,
        private WordService $wordService
    ) {
    }

    public function execute(int $id): string
    {
        $additional_agreement = $this->findAdditionalAgreementUseCase->execute($id);

        $merchant_info = MerchantInfo::query()->where('merchant_id', $additional_agreement->merchant_id)->first();
        if ($merchant_info === null) {
            throw new BusinessException('Доп информация не найдена', 'object_not_found', 404);
        }

        $template_path = match ($additional_agreement->document_type) {
            AdditionalAgreementDocumentTypeEnum::LIMIT()->getValue() => 'app/additional_agreement.docx',
            AdditionalAgreementDocumentTypeEnum::VAT()->getValue() => 'app/additional_agreement_vat.docx',
            AdditionalAgreementDocumentTypeEnum::DELIVERY()->getValue() => 'app/additional_agreement_delivery.docx',
            default => null
        };

        if ($template_path === null) {
            throw new  BusinessException('Не правильный тип документа', 'type_not_exists', 400);
        }

        return $this->wordService->createAdditionalAgreement($additional_agreement, $merchant_info, $template_path);
    }
}
