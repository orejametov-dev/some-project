<?php

namespace App\Console\Commands\Settings;

use App\Modules\Merchants\Models\Merchant;
use App\Services\Core\ServiceCore;
use Illuminate\Console\Command;

class UpdateCurrentSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:current_sales';

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
        $percentage_of_limit = Merchant::$percentage_of_limit;

        $amount_of_merchants = ServiceCore::request('GET', 'merchant-sales', []);

        foreach ($amount_of_merchants as $amount_of_merchant) {
            $merchant = Merchant::findOrFail($amount_of_merchant->merchant_id);
            $merchant->current_sales = $amount_of_merchant->discounted_amount;
            if ($merchant_info = $merchant->merchant_info) {
                $total_limit = $merchant_info->limit;
                $rest_limit = $merchant_info->limit - $merchant->current_sales;
                $merchant_info->rest_limit = $rest_limit > 0 ? $rest_limit : 0;
                if ($merchant->additional_agreements()->exists()) {
                    $additional_agreements = $merchant->additional_agreements;
                    foreach ($additional_agreements as $additional_agreement) {
                        $total_limit += $additional_agreement->limit;
                        $rest_limit = $total_limit - $merchant->current_sales;
                        $additional_agreement->rest_limit = $rest_limit > 0 ? $rest_limit : 0;
                        $additional_agreement->save();
                    }
                }
                $merchant_info->save();
            }
            $merchant->save();
        }

        Merchant::query()
            ->leftJoin("merchant_infos", "merchants.id", "=", "merchant_infos.merchant_id")
            ->whereRaw("IFNULL(merchant_infos.limit,0) {$percentage_of_limit} <= merchants.current_sales")
            ->whereNull('merchant_infos.limit_expired_at')
            ->update(["merchant_infos.limit_expired_at" => now()]);

        Merchant::query()
            ->leftJoin("merchant_infos", "merchants.id", "=", "merchant_infos.merchant_id")
            ->leftJoin('merchant_additional_agreements', 'merchants.id', '=', 'merchant_additional_agreements.merchant_id')
            ->whereRaw("(IFNULL(merchant_infos.limit, 0) + IFNULL(merchant_additional_agreements.limit, 0)) $percentage_of_limit <= merchants.current_sales")
            ->whereNull('merchant_additional_agreements.limit_expired_at')
            ->update(['merchant_additional_agreements.limit_expired_at' => now()]);
    }
}
