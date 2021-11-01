<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveForeignCompanyUserIdToAlifshopMerchantUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alifshop_merchant_accesses', function (Blueprint $table) {
            $table->dropForeign(['company_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alifshop_merchant_accesses', function (Blueprint $table) {
            //
        });
    }
}
