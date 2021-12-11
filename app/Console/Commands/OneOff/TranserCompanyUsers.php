<?php

namespace App\Console\Commands\OneOff;

use App\HttpServices\Company\CompanyService;
use App\HttpServices\Core\CoreService;
use App\Modules\Merchants\Models\Merchant;
use DB;
use Illuminate\Console\Command;

class TranserCompanyUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:company_users';

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
        DB::table('company_users')->chunkById(100, function ($company_users) {
            foreach ($company_users as $company_user) {
                CompanyService::createCompanyUserSpecial(
                    $company_user->id,
                    $company_user->user_id,
                    $company_user->company_id,
                    $company_user->phone,
                    $company_user->full_name
                );
            }
        });
    }
}
