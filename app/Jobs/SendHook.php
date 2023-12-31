<?php

declare(strict_types=1);

namespace App\Jobs;

use App\HttpRepositories\Hooks\DTO\HookData;
use App\HttpRepositories\Hooks\HooksHttpRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendHook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public HookData $hookData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(HookData $hookData)
    {
        self::onQueue('service-hook');
        $this->hookData = $hookData;
    }

    /**
     * Execute the job.
     *
     * @param HooksHttpRepository $hooksHttpRepository
     * @return void
     */
    public function handle(HooksHttpRepository $hooksHttpRepository)
    {
        $hooksHttpRepository->store($this->hookData);
    }
}
