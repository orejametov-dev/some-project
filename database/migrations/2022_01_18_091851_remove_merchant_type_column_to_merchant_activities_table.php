<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveMerchantTypeColumnToMerchantActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_activities', function (Blueprint $table) {
            $table->dropColumn('merchant_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant_activities', function (Blueprint $table) {
            $table->string('merchant_type')->after('id')->nullable();
        });
    }
}