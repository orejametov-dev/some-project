<?php

namespace App\DTOs\MerchantRequest;

use Alifuz\Utils\Entities\AbstractEntity;

final class UpdateMerchantRequestDTO extends AbstractEntity
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
        private ?int $stores_count = null,
        private ?int $merchant_users_count = null
    ) {
    }

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): static
    {
        return new static(
            user_name:  self::parseString($data['user_name']),
            user_phone:  self::parseString($data['user_phone']),
            name:  self::parseString($data['name']),
            legal_name:  self::parseString($data['legal_name']),
            legal_name_prefix:  self::parseString($data['legal_name_prefix']),
            categories:  self::parseArray($data['categories']),
            region: self::parseString($data['region']),
            district:  self::parseString($data['district']),
            approximate_sales: self::parseNullableInt($data['approximate_sales']),
            stores_count: self::parseNullableInt($data['stores_count']),
            merchant_users_count: self::parseNullableInt($data['merchant_users_count']),
        );
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        return [
            'stores_count' => $this->stores_count,
            'merchant_users_count' => $this->merchant_users_count,
        ];
    }

    /**
     * @return int|null
     */
    public function getStoresCount(): ?int
    {
        return $this->stores_count;
    }

    /**
     * @return int|null
     */
    public function getMerchantUsersCount(): ?int
    {
        return $this->merchant_users_count;
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
    public function getLegalNamePrefix(): string
    {
        return $this->legal_name_prefix;
    }

    /**
     * @return string
     */
    public function getLegalName(): string
    {
        return $this->legal_name;
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
}
