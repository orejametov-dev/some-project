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
    protected $signature = 'update:legal_name_prefix';

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
        MerchantInfo::query()->update(['legal_name_prefix' => 'LLC']);
        Merchant::query()->update(['legal_name_prefix' => 'LLC']);
        $this->info('Console command created successfully.');
    }
}
