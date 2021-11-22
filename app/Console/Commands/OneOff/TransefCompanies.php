<?php

namespace App\Console\Commands\OneOff;

use App\HttpServices\Company\CompanyService;
use App\HttpServices\Core\CoreService;
use App\Modules\Merchants\Models\Merchant;
use Carbon\Carbon;
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
    public function handle()
    {
        DB::table('companies')->chunkById(100, function ($companies) {
            foreach ($companies as $company) {
                $merchant = Merchant::where('company_id', $company->id)->first();
                CompanyService::createCompanyBySpecial(
                    $company->id,
                    $company->name,
                    $company->legal_name,
                    $company->legal_name_prefix,
                    $merchant->active ? "YES" : "NOT_ACTIVE",
                    Carbon::parse($company->created_at)->toString(),
                    Carbon::parse($company->updated_at)->toString(),
                );
            }
        });
    }
}
