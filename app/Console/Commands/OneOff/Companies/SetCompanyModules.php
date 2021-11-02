<?php

namespace App\Console\Commands\OneOff\Companies;

use App\Modules\Companies\Models\Company;
use App\Modules\Companies\Models\CompanyUser;
use DB;
use Illuminate\Console\Command;

class SetCompanyModules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:company_modules';

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
        $merchants = DB::table('merchants')->get(['id', 'company_id', 'active']);

        foreach ($merchants as $merchant) {
            DB::table('company_modules')->insert([
                'company_id' => $merchant->company_id,
                'module_id' => \App\Modules\Companies\Models\Module::AZO_MERCHANT,
                'active' => $merchant->active
            ]);
        }
    }
}
