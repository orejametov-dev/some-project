<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewRequestIdToMerchantFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_files', function (Blueprint $table) {
            $table->unsignedBigInteger('merchant_id')->nullable(true)->change();
            $table->unsignedBigInteger('request_id')->nullable()->after('merchant_id');
            $table->foreign('request_id')->references('id')->on('merchant_requests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant_files', function (Blueprint $table) {
            //
        });
    }
}
