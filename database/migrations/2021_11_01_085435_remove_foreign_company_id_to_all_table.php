<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveForeignCompanyIdToAllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchants', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
        });

        Schema::table('azo_merchant_accesses', function (Blueprint $table) {
            $table->dropForeign('merchant_users_company_user_id_foreign');
        });

        Schema::table('alifshop_merchants', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
        });

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
        Schema::table('merchants', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('companies');
        });

        Schema::table('azo_merchant_accesses', function (Blueprint $table) {
            $table->foreign('company_user_id')->references('id')->on('company_users');
        });

        Schema::table('alifshop_merchants', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('companies');
        });

        Schema::table('alifshop_merchant_accesses', function (Blueprint $table) {
            $table->foreign('company_user_id')->references('id')->on('company_users');
        });
    }
}
