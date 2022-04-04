<?php

declare(strict_types=1);

namespace App\DTOs\Notifications;

use Alifuz\Utils\Entities\AbstractEntity;
use Carbon\Carbon;

final class UpdateNotificationDTO extends AbstractEntity
{
    public function __construct(
        private string $title_ru,
        private string $title_uz,
        private string $body_ru,
        private string $body_uz,
        private ?Carbon $start_schedule,
        private ?Carbon $end_schedule = null,
    ) {
    }

    /**
     * @return string
     */
    public function getTitleRu(): string
    {
        return $this->title_ru;
    }

    /**
     * @return string
     */
    public function getTitleUz(): string
    {
        return $this->title_uz;
    }

    /**
     * @return string
     */
    public function getBodyRu(): string
    {
        return $this->body_ru;
    }

    /**
     * @return string
     */
    public function getBodyUz(): string
    {
        return $this->body_uz;
    }

    /**
     * @return Carbon|null
     */
    public function getStartSchedule(): ?Carbon
    {
        return $this->start_schedule;
    }

    /**
     * @return Carbon|null
     */
    public function getEndSchedule(): ?Carbon
    {
        return $this->end_schedule;
    }

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): static
    {
        return  new static(
            title_ru: self::parseString($data['title_ru']),
            title_uz: self::parseString($data['title_uz']),
            body_ru: self::parseString($data['body_ru']),
            body_uz: self::parseString($data['body_uz']),
            start_schedule: isset($data['start_schedule']) ? Carbon::parse($data['start_schedule']) : null,
            end_schedule: isset($data['end_schedule']) ? Carbon::parse($data['end_schedule']) : null,
        );
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize()
    {
        return  [
            'title_ru' => $this->title_ru,
            'title_uz' => $this->title_uz,
            'body_ru' => $this->body_ru,
            'body_uz' => $this->body_uz,
            'start_schedule' => $this->start_schedule,
            'end_schedule' => $this->end_schedule,
        ];
    }
}
