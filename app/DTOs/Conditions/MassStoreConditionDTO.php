<?php

declare(strict_types=1);

namespace App\DTOs\Conditions;

use Alifuz\Utils\Entities\AbstractEntity;
use Carbon\Carbon;

final class MassStoreConditionDTO extends AbstractEntity
{
    /**
     * @param int[] $merchant_ids
     * @param int[] $template_ids
     */
    public function __construct(
        private array $merchant_ids,
        private array $template_ids,
        private ?string $special_offer,
        private ?int $event_id,
        private bool $post_merchant,
        private bool $post_alifshop,
        private ?Carbon $started_at,
        private ?Carbon $finished_at,
    ) {
    }

    /**
     * @return int[]
     */
    public function getMerchantIds(): array
    {
        return $this->merchant_ids;
    }

    /**
     * @return int[]
     */
    public function getTemplateIds(): array
    {
        return $this->template_ids;
    }

    /**
     * @return string|null
     */
    public function getSpecialOffer(): ?string
    {
        return $this->special_offer;
    }

    /**
     * @return int|null
     */
    public function getEventId(): ?int
    {
        return $this->event_id;
    }

    /**
     * @return bool
     */
    public function isPostMerchant(): bool
    {
        return $this->post_merchant;
    }

    /**
     * @return bool
     */
    public function isPostAlifshop(): bool
    {
        return $this->post_alifshop;
    }

    /**
     * @return Carbon|null
     */
    public function getStartedAt(): ?Carbon
    {
        return $this->started_at;
    }

    /**
     * @return Carbon|null
     */
    public function getFinishedAt(): ?Carbon
    {
        return $this->finished_at;
    }

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): static
    {
        return new static(
            merchant_ids: self::parseArray($data['merchant_ids']),
            template_ids:  self::parseArray($data['template_ids']),
            special_offer:  self::parseNullableString($data['special_offer']),
            event_id:  self::parseNullableInt($data['event_id']),
            post_merchant:  self::parseBool($data['post_merchant']),
            post_alifshop:  self::parseBool($data['post_alifshop']),
            started_at:  self::parseNullableCarbon($data['started_at']),
            finished_at:  self::parseNullableCarbon($data['finished_at']),
        );
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize()
    {
        return [
            'merchant_ids' => $this->merchant_ids,
            'template_ids' => $this->template_ids,
            'special_offer' => $this->special_offer,
            'event_id' => $this->event_id,
            'post_merchant' => $this->post_merchant,
            'post_alifshop' => $this->post_alifshop,
            'started_at' => $this->started_at,
            'finished_at' => $this->finished_at,
        ];
    }
}
