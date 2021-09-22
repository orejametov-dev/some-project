<?php

namespace App\Console\Commands\OneOff\Companies;

use App\Modules\Companies\Models\Company;
use App\Modules\Companies\Models\CompanyUser;
use Illuminate\Console\Command;

class MigrateMerchantUsersToCompanyUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:merchant_users_to_company_users';

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
        Company::query()->chunkById(100, function ($companies) {
            foreach ($companies as $company) {
                $azo_merchant_accesses = $company->merchant->azo_merchant_access()->get();
                foreach ($azo_merchant_accesses as $azo_merchant_access) {
                    $company_user = new CompanyUser();
                    $company_user->user_id = $azo_merchant_access->user_id;
                    $company_user->company_id = $company->id;
                    $company_user->save();

                    $azo_merchant_access->company_user_id = $company->id;
                    $azo_merchant_access->save();
                }
            }
        });
    }
}
