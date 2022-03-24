<?php

namespace App\Mappings;

use App\Enums\MerchantRequestStatusEnum;

final class MerchantRequestStatusMapping
{
    public function __construct(
        private array $mapping = []
    ) {
        $this->mapping = [
            MerchantRequestStatusEnum::NEW()->getValue() => [
                'id' => MerchantRequestStatusEnum::NEW(),
                'name' => 'Новый',
            ],
            MerchantRequestStatusEnum::ALLOWED()->getValue() => [
                'id' => MerchantRequestStatusEnum::ALLOWED(),
                'name' => 'Одобрено',
            ],
            MerchantRequestStatusEnum::TRASH()->getValue() => [
                'id' => MerchantRequestStatusEnum::TRASH(),
                'name' => 'В корзине',
            ],
            MerchantRequestStatusEnum::IN_PROCESS()->getValue() => [
                'id' => MerchantRequestStatusEnum::IN_PROCESS(),
                'name' => 'На переговорах',
            ],
            MerchantRequestStatusEnum::ON_TRAINING()->getValue() => [
                'id' => MerchantRequestStatusEnum::ON_TRAINING(),
                'name' => 'На обучении',
            ],
        ];
    }

    public function getMappedValue(MerchantRequestStatusEnum $merchantRequestStatusEnum): array
    {
        return $this->mapping[$merchantRequestStatusEnum->getValue()];
    }

    public function getMappings(): array
    {
        return array_values($this->mapping);
    }
}
