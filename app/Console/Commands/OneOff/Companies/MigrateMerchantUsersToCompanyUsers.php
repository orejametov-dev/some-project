<?php

namespace App\Console\Commands\OneOff\Companies;

use App\Modules\Companies\Models\Company;
use App\Modules\Companies\Models\CompanyUser;
use App\Modules\Merchants\Models\AzoMerchantAccess;
use App\Modules\Merchants\Models\Merchant;
use Illuminate\Console\Command;

class MigrateMerchantUsersToCompanyUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:company_user_id';

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
        $azo_merchant_accesses = AzoMerchantAccess::whereNull('company_user_id')->get();

        foreach ($azo_merchant_accesses as $azo_merchant_access) {
            $company_user = new CompanyUser();
            $company_user->user_id = $azo_merchant_access->user_id;
            $company_user->company_id = $azo_merchant_access->merchant->company_id;
            $company_user->full_name = $azo_merchant_access->user_name;
            $company_user->phone = $azo_merchant_access->phone;

            $company_user->save();

            $azo_merchant_access->company_user_id = $company_user->id;
            $azo_merchant_access->save();
        }
    }
}
