<?php

declare(strict_types=1);

namespace App\Jobs;

use App\HttpRepositories\Auth\AuthHttpRepository;
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
     * @param AuthHttpRepository $authHttpRepository
     * @return void
     */
    public function handle(AuthHttpRepository $authHttpRepository)
    {
        if ($this->type === AuthHttpRepository::ACTIVATE_MERCHANT_ROLE) {
            $authHttpRepository->store($this->user_id);
        } elseif ($this->type === AuthHttpRepository::DEACTIVATE_MERCHANT_ROLE) {
            $authHttpRepository->remove($this->user_id);
        }
    }
}
