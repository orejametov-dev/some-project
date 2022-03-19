<?php

namespace App\Mappings;

use App\Enums\ProblemCaseStatusEnum;

final class ProblemCaseStatusMapping
{
    public function __construct(
        private array $mapping = []
    ) {
        $this->mapping = [
            ProblemCaseStatusEnum::NEW()->getValue() => [
                'id' => ProblemCaseStatusEnum::NEW(),
                'name' => 'Новый',
                'lang' => [
                    'uz' => 'Yangi',
                    'ru' => 'Новый',
                ],
            ],
            ProblemCaseStatusEnum::IN_PROCESS()->getValue() => [
                'id' => ProblemCaseStatusEnum::IN_PROCESS(),
                'name' => 'В процессе',
                'lang' => [
                    'uz' => 'Ko`rib chiqilmoqda',
                    'ru' => 'В процессе',
                ],
            ],
            ProblemCaseStatusEnum::DONE()->getValue() => [
                'id' => ProblemCaseStatusEnum::DONE(),
                'name' => 'Выполнено',
                'lang' => [
                    'uz' => 'Bajarildi',
                    'ru' => 'Выполнено',
                ],
            ],
            ProblemCaseStatusEnum::FINISHED()->getValue() => [
                'id' => ProblemCaseStatusEnum::FINISHED(),
                'name' => 'Завершен',
                'lang' => [
                    'uz' => 'Tugatildi',
                    'ru' => 'Завершено',
                ],
            ],
        ];
    }

    public function getMappedValue(ProblemCaseStatusEnum $merchantRequestStatusEnum): array
    {
        return $this->mapping[$merchantRequestStatusEnum->getValue()];
    }

    public function getMappings(): array
    {
        return array_values($this->mapping);
    }
}
