<?php

namespace App\Console\Commands\OneOff;

use App\Modules\Merchants\Models\ProblemCase;
use Illuminate\Console\Command;
use function Clue\StreamFilter\fun;

class SplittingClientDataToColumns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:clients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ProblemCase::query()->chunkById(100 , function ($problem_cases) {
           foreach ($problem_cases as $problem_case) {
               $user = explode(' ' , $problem_case->search_index);
               $problem_case->client_name  = $user[0];
               $problem_case->client_surname = $user[1];
               $problem_case->client_patronymic = $user[2];
               $problem_case->phone = $user[3];
               $problem_case->save();
           }
        });
    }
}
