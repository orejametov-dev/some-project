<?php

namespace App\Jobs;

use App\HttpServices\Notify\NotifyMicroService;
use App\Services\SMS\SmsMessages;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Arr;

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
    public function handle()
    {
        NotifyMicroService::sendSms($this->phone, $this->message , NotifyMicroService::PROBLEM_CASE);
    }
}