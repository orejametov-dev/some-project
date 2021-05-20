<?php

use App\Modules\Merchants\Services\MerchantStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusKeyColumnsToMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchants', function (Blueprint $table) {
            $table->smallInteger('status_id')->default(MerchantStatus::ACTIVE);
            $table->string('status_key')->default(MerchantStatus::getOneById(MerchantStatus::ACTIVE)->key);
            $table->timestamp('status_updated_at')->default(now());
        });

//        $merchants = \App\Modules\Merchants\Models\Merchant::chunkById(100, function ($merchants) {
//            foreach ($merchants as $merchant) {
//                $merchant->timestamps = false;
//                $merchant->setStatusActive();
//                $merchant->save();
//            }
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchants', function (Blueprint $table) {
            //
        });
    }
}
