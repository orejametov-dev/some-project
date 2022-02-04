<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\HttpRepositories\Notify\NotifyHttpRepository;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $message;
    public string $phone;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($phone , $message)
    {
        self::onQueue('service-notify');
        $this->phone = $phone;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(NotifyHttpRepository $notifyHttpRepository)
    {
        $notifyHttpRepository->sendSms($this->phone, $this->message , NotifyHttpRepository::PROBLEM_CASE);
    }
}
