<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToMerchantRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_requests', function (Blueprint $table) {
//            $table->string('name')->nullable(true)->change();

            $table->json('categories');
            $table->integer('stores_count');
            $table->string('address')->nullable();
            $table->integer('merchant_users_count');
            $table->unsignedBigInteger('approximate_sales');
            $table->string('token');

            $table->string('director_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('mfo')->nullable();
            $table->string('tin')->nullable();
            $table->string('oked')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_name')->nullable();
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
            //
        });
    }
}
