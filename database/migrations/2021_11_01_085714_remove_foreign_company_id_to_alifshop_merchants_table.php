<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveForeignCompanyIdToAlifshopMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alifshop_merchants', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alifshop_merchants', function (Blueprint $table) {
            //
        });
    }
}
