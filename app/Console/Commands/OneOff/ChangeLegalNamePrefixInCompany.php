<?php

namespace App\Console\Commands\OneOff;

use App\Modules\Companies\Models\Company;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\MerchantInfo;
use Illuminate\Console\Command;

class ChangeLegalNamePrefixInCompany extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:legal_name_prefix_ooo';

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
        Merchant::query()->where('legal_name', 'LIKE', 'OOO%')
            ->chunkById(50, function ($merchants){
            foreach ($merchants as $merchant) {
                $merchant->legal_name = trim(trim(explode('OOO', $merchant->legal_name)[1], ' '), '"');
                $merchant->save();
            }
        });

        Company::query()->where('legal_name', 'LIKE', 'OOO%')
            ->chunkById(50, function ($companies){
                foreach ($companies as $company) {
                    $company->legal_name = trim(trim(explode('OOO', $company->legal_name)[1], ' '), '"');
                    $company->save();
                }
            });
    }
}
