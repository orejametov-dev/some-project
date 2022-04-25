<?php

namespace Tests\Unit\ApplicationConditions;

use App\Exceptions\NotFoundException;
use App\Models\Condition;
use App\Repositories\ApplicationConditionRepository;
use App\UseCases\ApplicationConditions\FindConditionByIdUseCase;
use Tests\TestCase;

class FindConditionByIdUseCaseTest extends TestCase
{
    private ApplicationConditionRepository $applicationConditionRepository;
    private FindConditionByIdUseCase $findConditionByIdUseCase;

    public function setUp(): void
    {
        parent::setUp();

        $this->applicationConditionRepository = $this->createMock(ApplicationConditionRepository::class);
        $this->findConditionByIdUseCase = new FindConditionByIdUseCase($this->applicationConditionRepository);
    }

    public function testNotFound()
    {
        $this->expectException(NotFoundException::class);
        $this->applicationConditionRepository->method('getById')->willReturn(null);
        $this->findConditionByIdUseCase->execute(1);
    }

    public function testSuccess()
    {
        $condition = new Condition();
        $condition->id = 1;
        $condition->merchant_id = 1;
        $condition->store_id = 1;
        $condition->commission = 1;
        $condition->duration = 1;
        $condition->discount = 1;
        $condition->is_special = false;
        $condition->special_offer = 'test';
        $condition->event_id = 1;
        $condition->post_merchant = 1;
        $condition->post_alifshop = 1;
        $condition->active = false;

        $this->applicationConditionRepository->method('getById')->willReturn($condition);
        $response = $this->findConditionByIdUseCase->execute($condition->id);

        static::assertIsObject($condition, $response);
    }
}
