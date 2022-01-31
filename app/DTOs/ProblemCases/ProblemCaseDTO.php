<?php

declare(strict_types=1);

namespace App\DTOs\ProblemCases;

use Alifuz\Utils\Parser\ParseDataTrait;
use App\Exceptions\BusinessException;

class ProblemCaseDTO
{
    use ParseDataTrait;

    public function __construct(
        public ?string  $description,
        private ?int    $application_id,
        private ?string $credit_number,
    )
    {
    }

    public static function fromArray(array $data)
    {
        return new self(
            self::parseNullableString($data['description']),
            self::parseNullableInt($data['application_id']),
            self::parseNullableString($data['credit_number']),

        );
    }

    public function getIdentifier(): string|int
    {
        if ($this->application_id === null && $this->credit_number === null) {
            throw new BusinessException('Номер заявки или номер кредита не были переданны' , 'wrong_number', 404);
        }

        return $this->application_id ?? $this->credit_number;
    }
}
