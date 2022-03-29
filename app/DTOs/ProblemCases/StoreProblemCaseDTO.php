<?php

declare(strict_types=1);

namespace App\DTOs\ProblemCases;

use Alifuz\Utils\Entities\AbstractEntity;
use App\Exceptions\BusinessException;

final class StoreProblemCaseDTO extends AbstractEntity
{
    public function __construct(
        private string $description,
        private ?int $application_id,
        private ?string $credit_number,
    ) {
    }

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): static
    {
        return new static(
            description: self::parseString($data['description']),
            application_id: self::parseNullableInt($data['application_id']),
            credit_number: self::parseNullableString($data['credit_number']),
        );
    }

    public function getIdentifier(): string|int
    {
        if ($this->application_id === null && $this->credit_number === null) {
            throw new BusinessException('Номер заявки или номер кредита не были переданны', 'wrong_number', 404);
        }

        return $this->application_id ?? $this->credit_number;
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize()
    {
        return [
            'description' => $this->description,
            'application_id' => $this->application_id,
            'credit_number' => $this->credit_number,
        ];
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}
