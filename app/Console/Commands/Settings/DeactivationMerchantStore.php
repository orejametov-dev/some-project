<?php

namespace App\Console\Commands\Settings;

use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Store;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\HttpServices\Core\CoreService;

class DeactivationMerchantStore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:deactivation';

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
        $from_date = Carbon::now()->subWeeks(2)->format('Y-m-d');;
        $to_date = Carbon::now()->format('Y-m-d');

        Merchant::where('active' , true)
            ->chunkById(20, function ($merchants) use ($coreService, $from_date, $to_date) {
                foreach ($merchants as $merchant)
                {
                    $result = $coreService->getMerchantApplicationsAndClientsCountByRange($merchant->id,$from_date, $to_date);
                    $resultData = $result['data'];
                    if($resultData['applications_count'] == 0 && $resultData['clients_count'] == 0)
                    {
                        $merchant->active = false;
                        $merchant->save();
                    }
                }
            });


        Store::whereHas('merchant', function($query) {
            $query->where('active', true);
        })->where('active' , true)
            ->chunkById(20, function ($stores) use ($coreService, $from_date, $to_date) {
                foreach ($stores as $store) {
                    $result = $coreService->getStoreApplicationsAndClientsCountByRange($store->id, $from_date, $to_date);
                    $resultData = $result['data'];
                    if ($resultData['applications_count'] == 0 && $resultData['clients_count'] == 0) {
                        $store->active = false;
                        $store->save();

                    }
                }
            });
    }
}


//1.Туториал по many-to-many relation(store, index, remove)

//2.Создать миграцию

//a. php artisan make:migration make_nullable_some_columns_on_merchant_activities_table --table=merchant_activities
//columns:created_by_id, created_by_name
//b. php artisan make:migration make_nullable_some_columns_on_store_activities_table --table=store_activities

//3.Добавить причину для деактивации, взять примерез из текущих контроллеров

//ApiGateway//Merchants//MerchantsController@toggle
//ApiGateway//Stores//StoresController@toggle
