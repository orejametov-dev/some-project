<?php

namespace Database\Seeders;

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
           MerchantsSeeder::class,
           StoresSeeder::class,
           MerchantUsersSeeder::class,
           MerchantInfosSeeder::class,
           MerchantAdditionalAgreementsSeeder::class,
           ApplicationConditionsSeeder::class,
           MerchantTags::class,
           MerchantTag::class,
           CompetitorsSeeder::class
       ]);
    }
}
