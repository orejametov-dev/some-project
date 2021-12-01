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

class SendProblemCaseSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $name;
    public int $problem_case_id;
    public string $phone;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($name , $phone , $problem_case_id)
    {
        $this->name = Arr::first($name);
        $this->phone = Arr::first($phone);
        $this->problem_case_id = $problem_case_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
            $message = SmsMessages::onNewProblemCases($this->name, $this->problem_case_id);
            NotifyMicroService::sendSms($this->phone, $message);
    }
}
