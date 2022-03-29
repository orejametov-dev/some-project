<?php

declare(strict_types=1);

namespace App\DTOs\Complaints;

use Alifuz\Utils\Entities\AbstractEntity;
use App\DTOs\Competitors\CompetitorClientDTO;

final class StoreComplaintDTO extends AbstractEntity
{
    public function __construct(
        private int $user_id,
        private CompetitorClientDTO $meta
    ) {
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getMeta(): CompetitorClientDTO
    {
        return $this->meta;
    }

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): static
    {
        return new static(
            user_id: self::parseInt($data['user_id']),
            meta: self::parseEntity(CompetitorClientDTO::class, $data['meta'])
        );
    }

    public function jsonSerialize()
    {
        return [
            'user_id' => $this->user_id,
            'meta' => $this->meta,
        ];
    }
}
