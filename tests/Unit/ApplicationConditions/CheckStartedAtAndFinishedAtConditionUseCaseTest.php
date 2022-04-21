<?php

namespace Tests\Unit\ApplicationConditions;

use App\Exceptions\BusinessException;
use App\UseCases\ApplicationConditions\CheckStartedAtAndFinishedAtConditionUseCase;
use Carbon\Carbon;
use Tests\TestCase;

class CheckStartedAtAndFinishedAtConditionUseCaseTest extends TestCase
{
    private CheckStartedAtAndFinishedAtConditionUseCase $checkStartedAtAndFinishedAtConditionUseCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->checkStartedAtAndFinishedAtConditionUseCase = new CheckStartedAtAndFinishedAtConditionUseCase();
    }

    public function testStartedAtIsLessThanToday()
    {
        $this->expectException(BusinessException::class);
        $this->checkStartedAtAndFinishedAtConditionUseCase->execute(Carbon::parse('2022-04-20'), null);
    }

    public function testFinishedAtIsLessOrEqualThanToday()
    {
        $this->expectException(BusinessException::class);
        $this->checkStartedAtAndFinishedAtConditionUseCase->execute(Carbon::parse('2022-04-22'), Carbon::now());
    }

    public function testFinishedAtIsLessOrEqualThanStartedAt()
    {
        $this->expectException(BusinessException::class);
        $this->checkStartedAtAndFinishedAtConditionUseCase->execute(Carbon::parse('2022-04-22'), Carbon::parse('2022-04-22'));
    }
}
