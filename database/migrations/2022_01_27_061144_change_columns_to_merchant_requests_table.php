<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsToMerchantRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_requests', function (Blueprint $table) {
            $table->dropColumn('token');
            $table->integer('stores_count')->nullable()->change();
            $table->integer('merchant_users_count')->nullable()->change();
            $table->text('information')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant_requests', function (Blueprint $table) {
            $table->string('token');
            $table->integer('stores_count')->change();
            $table->integer('merchant_users_count')->change();
            $table->text('information')->change();
        });
    }
}
