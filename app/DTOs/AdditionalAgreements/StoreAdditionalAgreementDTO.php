<?php

declare(strict_types=1);

namespace App\DTOs\AdditionalAgreements;

use Alifuz\Utils\Entities\AbstractEntity;
use Carbon\Carbon;

final class StoreAdditionalAgreementDTO extends AbstractEntity
{
    public function __construct(
        private Carbon $registration_date,
        private int $number,
        private int $merchant_id,
        private string $document_type,
        private ?int $limit = null,
    ) {
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @return int
     */
    public function getMerchantId(): int
    {
        return $this->merchant_id;
    }

    /**
     * @return string
     */
    public function getDocumentType(): string
    {
        return $this->document_type;
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getRegistrationDate(): Carbon
    {
        return $this->registration_date;
    }

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): static
    {
        return new static(
            registration_date: Carbon::parse($data['registration_date']),
            number: self::parseInt($data['number']),
            merchant_id: self::parseInt($data['merchant_id']),
            document_type: self::parseString($data['document_type']),
            limit: self::parseNullableInt($data['limit']),
        );
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize()
    {
        return [
            'limit' => $this->limit,
            'registration_date' => $this->registration_date,
            'number' => $this->number,
            'merchant_id' => $this->merchant_id,
            'document_type' => $this->document_type,
        ];
    }
}
