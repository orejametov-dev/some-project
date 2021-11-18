<?php

namespace App\Console\Commands\OneOff;

use App\HttpServices\Company\CompanyService;
use App\HttpServices\Core\CoreService;
use DB;
use Illuminate\Console\Command;

class TransefCompanies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:company';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command deactivation Merchant and Store';

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
    public function handle(CoreService $coreService)
    {
        DB::table('companies')->chunkById(100, function ($companies) {
            foreach ($companies as $company) {
                CompanyService::createCompany(
                    $company->name,
                    $company->legal_name,
                    $company->legal_name_prefix
                );
            }
        });

        DB::table('company_users')->chunkById(100, function ($company_users) {
            foreach ($company_users as $company_user) {
                CompanyService::createCompanyUser(
                    $company_user->user_id,
                    $company_user->company_id,
                    $company_user->phone,
                    $company_user->full_name
                );
            }
        });
    }
}
