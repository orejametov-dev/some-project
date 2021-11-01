<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveForeignCompanyUserIdToMerchantUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('azo_merchant_accesses', function (Blueprint $table) {
            $table->dropForeign('merchant_users_company_user_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('azo_merchant_accesses', function (Blueprint $table) {
            //
        });
    }
}
