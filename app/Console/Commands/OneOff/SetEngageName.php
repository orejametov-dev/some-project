<?php

namespace App\Console\Commands\OneOff;

use App\Modules\Merchants\Models\Request;
use App\Services\Core\ServiceCore;
use Illuminate\Console\Command;

class SetEngageName extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:engage_by_name';

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
        Request::query()->chunk(50, function ($requests){
            foreach ($requests as $request){
                $user = ServiceCore::request('GET', 'users', new \Illuminate\Http\Request([
                    'id' => $request->engaged_by_id,
                    'object' => 'true'
                ]));

                $request->engaged_by_name = $user->name;
                $request->save();
            }
        });

    }
}
