<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMerchantTypeToMerchantActivities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_activities', function (Blueprint $table) {
            $table->string('merchant_type')->after('id')->nullable();
        });

        DB::table('merchant_activities')->update([
            'merchant_type' => \App\Modules\Merchants\Models\Merchant::class,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant_activities', function (Blueprint $table) {
            $table->dropColumn('merchant_type');
        });
    }
}
