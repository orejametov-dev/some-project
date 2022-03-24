<?php

declare(strict_types=1);

namespace App\DTOs\Auth;

class AzoAccessDto
{
    public function __construct(
        public int $merchant_id,
        public int $store_id,
        public int $id
    ) {
    }
}
