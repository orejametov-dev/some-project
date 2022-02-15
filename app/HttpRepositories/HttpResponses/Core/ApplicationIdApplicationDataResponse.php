<?php

namespace App\HttpRepositories\HttpResponses\Core;

use Alifuz\Utils\Parser\ParseDataTrait;
use Carbon\Carbon;

class ApplicationIdApplicationDataResponse
{
    use ParseDataTrait;

    public function __construct(
        public int $id,
        public int $merchant_id,
        public int $store_id,
        public int $client_id,
        public string $client_name,
        public string $client_surname,
        public string $client_patronymic,
        public string $phone,
        public ?array $application_items,
        public int $post_or_pre_created_by_id,
        public string $post_or_pre_created_by_name,
        public ?Carbon $application_created_at,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            self::parseInt($data['id']),
            self::parseInt($data['merchant_id']),
            self::parseInt($data['store_id']),
            self::parseInt($data['client']['id']),
            self::parseString($data['client']['name']),
            self::parseString($data['client']['surname']),
            self::parseString($data['client']['patronymic']),
            self::parseString($data['client']['phone']),
            self::parseNullableArray($data['application_items']),
            self::parseNullableInt($data['merchant_engaged_by']['id']),
            self::parseNullableString($data['merchant_engaged_by']['name']),
            Carbon::parse($data['created_at'])
        );
    }
}
