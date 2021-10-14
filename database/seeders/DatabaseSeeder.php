<?php

namespace Database\Seeders;

use App\Modules\AlifshopMerchants\Models\AlifshopMerchantStore;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       $this->call([
           WebServicesSeeder::class,
           CompaniesSeeder::class,
           CompanyUsersSeeder::class,
           MerchantsSeeder::class,
           StoresSeeder::class,
           MerchantUsersSeeder::class,
           MerchantInfosSeeder::class,
           MerchantAdditionalAgreementsSeeder::class,
//           MerchantRequestsSeeder::class,
           ApplicationConditionsSeeder::class,
           MerchantTags::class,
           MerchantTag::class,
           AlifshopMerchantSeeder::class,
//           AlifshopMerchantStoreSeeder::class,
           AlifshopMerchantAccessSeeder::class
       ]);
    }
}
