<?php

declare(strict_types=1);

namespace App\DTOs\Merchants;

use Alifuz\Utils\Entities\AbstractEntity;

final class UpdateMerchantDTO extends AbstractEntity
{
    public function __construct(
        private string $name,
        private string $legal_name,
        private string $legal_name_prefix,
        private string $token,
        private int $min_application_price
    ) {
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
    public function getLegalName(): string
    {
        return $this->legal_name;
    }

    /**
     * @return string
     */
    public function getLegalNamePrefix(): string
    {
        return $this->legal_name_prefix;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return int
     */
    public function getMinApplicationPrice(): int
    {
        return $this->min_application_price;
    }

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): static
    {
        return new static(
            name: self::parseString($data['name']),
            legal_name: self::parseString($data['legal_name']),
            legal_name_prefix: self::parseString($data['legal_name_prefix']),
            token: self::parseString($data['token']),
            min_application_price: self::parseInt($data['min_application_price'])
        );
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'legal_name' => $this->legal_name,
            'legal_name_prefix' => $this->legal_name_prefix,
            'token' => $this->token,
            'min_application_price' => $this->min_application_price,
        ];
    }
}
