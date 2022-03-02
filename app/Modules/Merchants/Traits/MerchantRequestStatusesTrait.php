<?php

namespace App\Modules\Merchants\Traits;

use App\Modules\Merchants\Models\Request;
use Illuminate\Database\Eloquent\Builder;

trait MerchantRequestStatusesTrait
{
    public function isStatusNew(): bool
    {
        return $this->status_id == self::NEW;
    }

    public function isInProcess(): bool
    {
        return $this->status_id == self::IN_PROCESS;
    }

    public function isOnTraining(): bool
    {
        return $this->status_id == self::ON_TRAINING;
    }

    public function isStatusAllowed(): bool
    {
        return $this->status_id == self::ALLOWED;
    }

    public function isStatusTrash(): bool
    {
        return $this->status_id == self::TRASH;
    }

    public function scopeNew(Builder $builder): Builder
    {
        return $builder->where('status_id', self::NEW);
    }

    public function scopeInProcess(Builder $builder): Builder
    {
        return $builder->where('status_id', self::IN_PROCESS);
    }

    public function scopeOnTraining(Builder $builder): Builder
    {
        return $builder->where('status_id', self::ON_TRAINING);
    }

    public function scopeAllowed(Builder $builder): Builder
    {
        return $builder->where('status_id', self::ALLOWED);
    }

    public function scopeTrash(Builder $builder): Builder
    {
        return $builder->where('status_id', self::TRASH);
    }

    public function setStatusNew(): Request
    {
        $status = self::getOneById(self::NEW);
        $this->status_updated_at = now();
        $this->status_id = $status->id;

        return $this;
    }

    public function setStatusInProcess(): self
    {
        return $this->setStatus(self::IN_PROCESS);
    }

    public function setStatusOnTraining(): self
    {
        return $this->setStatus(self::ON_TRAINING);
    }

    public function setStatusAllowed(): self
    {
        return $this->setStatus(self::ALLOWED);
    }

    public function setStatusTrash(): self
    {
        return $this->setStatus(self::TRASH);
    }

    public function setStatus(int $status_id): Request
    {
        $status = self::getOneById($status_id);
        $this->assertStateSwitchTo($status->id);
        $this->status_updated_at = now();
        $this->status_id = $status_id;

        return $this;
    }
}
