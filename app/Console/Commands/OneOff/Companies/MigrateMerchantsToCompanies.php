<?php

namespace App\Console\Commands\OneOff\Companies;

use App\Modules\Companies\Models\Company;
use App\Modules\Merchants\Models\Merchant;
use Illuminate\Console\Command;

class MigrateMerchantsToCompanies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:merchant_to_companies';

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
        Merchant::query()->chunkById(50, function ($merchants){
            foreach ($merchants as $merchant) {
                $company = new Company([
                    'name' => $merchant->name,
                    'legal_name' => $merchant->legal_name
                ]);
                $company->id = $merchant->id;

                $company->save();

                $merchant->company_id = $company->id;
                $merchant->save();
            }
        });


    }
}