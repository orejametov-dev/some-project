<?php

declare(strict_types=1);

namespace App\Jobs;

use App\HttpServices\Auth\AuthMicroService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ToggleMerchantRoleOfUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $user_id;
    public string $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $user_id, string $type)
    {
        self::onQueue('service-auth');
        $this->user_id = $user_id;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @param AuthMicroService $authMicroService
     * @return void
     */
    public function handle(AuthMicroService $authMicroService)
    {
        if ($this->type === AuthMicroService::ACTIVATE_MERCHANT_ROLE) {
            $authMicroService->store($this->user_id);
        } elseif ($this->type === AuthMicroService::DEACTIVATE_MERCHANT_ROLE) {
            $authMicroService->remove($this->user_id);
        }
    }
}
