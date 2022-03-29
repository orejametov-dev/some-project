<?php

declare(strict_types=1);

namespace App\DTOs\Competitors;

use Alifuz\Utils\Entities\AbstractEntity;

final class CompetitorClientDTO extends AbstractEntity
{
    public function __construct(
        private int $id,
        private string $name,
        private string $surname,
        private string $patronymic,
        private array $reason_correction
    ) {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @return string
     */
    public function getPatronymic(): string
    {
        return $this->patronymic;
    }

    /**
     * @return array
     */
    public function getReasonCorrection(): array
    {
        return $this->reason_correction;
    }

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): static
    {
        return new static(
            id: self::parseInt($data['client_id']),
            name: self::parseString($data['client_name']),
            surname: self::parseString($data['client_surname']),
            patronymic: self::parseString($data['client_patronymic']),
            reason_correction: self::parseArray($data['reason_correction'])
        );
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'patronymic' => $this->patronymic,
            'reason_correction' => $this->reason_correction,
        ];
    }
}
