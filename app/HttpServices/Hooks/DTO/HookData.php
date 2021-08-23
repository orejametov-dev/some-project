<?php

namespace App\HttpServices\Hooks\DTO;

class HookData
{
    public function __construct(
        public string $service,
        public string $hookable_type,
        public int $hookable_id,
        public string $created_from_str,
        public int $created_by_id,
        public string $body,
        public string $keyword,
        public string $action,
        public ?string $class = null,
        public ?string $action_at = null,
        public ?string $created_by_str = '',
    )
    {
    }
}
