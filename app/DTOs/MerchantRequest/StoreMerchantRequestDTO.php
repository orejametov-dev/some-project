<?php

declare(strict_types=1);

namespace App\DTOs\MerchantRequest;

use Alifuz\Utils\Entities\AbstractEntity;

final class StoreMerchantRequestDTO extends AbstractEntity
{
    public function __construct(
        private string $user_name,
        private string $user_phone,
        private string $name,
        private string $legal_name,
        private string $legal_name_prefix,
        private array $categories,
        private string $region,
        private string $district,
        private ?int $approximate_sales = null,
        private ?string $address = null,
    ) {
    }

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): static
    {
        return new static(
            self::parseString($data['user_name']),
            self::parseString($data['user_phone']),
            self::parseString($data['name']),
            self::parseString($data['legal_name']),
            self::parseString($data['legal_name_prefix']),
            self::parseArray($data['categories']),
            self::parseString($data['region']),
            self::parseString($data['district']),
            self::parseNullableInt($data['approximate_sales']),
            self::parseNullableString($data['address']),
        );
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        return  [
            'user_name' => $this->user_name,
            'user_phone' => $this->user_phone,
            'name' => $this->name,
            'legal_name' => $this->legal_name,
            'legal_name_prefix' => $this->legal_name_prefix,
            'categories' => $this->categories,
            'region' => $this->region,
            'district' => $this->district,
            'approximate_sales' => $this->approximate_sales,
            'address' => $this->address,
        ];
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->user_name;
    }

    /**
     * @return string
     */
    public function getUserPhone(): string
    {
        return $this->user_phone;
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
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @return string
     */
    public function getDistrict(): string
    {
        return $this->district;
    }

    /**
     * @return int|null
     */
    public function getApproximateSales(): ?int
    {
        return $this->approximate_sales;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }
}
