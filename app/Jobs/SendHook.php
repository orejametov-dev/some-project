<?php

namespace App\Jobs;

use App\HttpServices\Hooks\DTO\HookData;
use App\HttpServices\Hooks\HooksMicroService;
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
     * @param HooksMicroService $hooksMicroService
     * @return void
     */
    public function handle(HooksMicroService $hooksMicroService)
    {
        $hooksMicroService->store($this->hookData);
    }
}
