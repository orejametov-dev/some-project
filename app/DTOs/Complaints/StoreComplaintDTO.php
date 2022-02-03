<?php

declare(strict_types=1);

namespace App\DTOs\Complaints;

use Alifuz\Utils\Parser\ParseDataTrait;

class StoreComplaintDTO
{
    use ParseDataTrait;

    public function __construct(
        public int $user_id,
        public string $reason_correction
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            self::parseInt($data['user_id']),
            self::parseString($data['reason_correction'])
        );
    }
}
