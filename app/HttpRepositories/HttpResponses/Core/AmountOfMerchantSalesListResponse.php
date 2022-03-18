<?php

declare(strict_types=1);

namespace App\HttpRepositories\HttpResponses\Core;

use Alifuz\Utils\Parser\ParseDataTrait;

/**
 * @property AmountOfMerchantSalesResponse[] $entities
 */
class AmountOfMerchantSalesListResponse
{
    use ParseDataTrait;

    public function __construct(
        private array $entities,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            array_map(static function ($permission) {
                return AmountOfMerchantSalesResponse::fromArray((array) $permission);
            }, $data),
        );
    }

    /**
     * @return AmountOfMerchantSalesResponse[]
     */
    public function getEntities(): array
    {
        return $this->entities;
    }
}
