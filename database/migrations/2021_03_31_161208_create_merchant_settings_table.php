<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_settings', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('request_status_new')->unsigned();
            $table->foreign('request_status_new')->references('id')->on('merchant_request_statuses');

            $table->bigInteger('request_status_allowed')->unsigned();
            $table->foreign('request_status_allowed')->references('id')->on('merchant_request_statuses');

            $table->bigInteger('request_status_trash')->unsigned();
            $table->foreign('request_status_trash')->references('id')->on('merchant_request_statuses');

            $table->bigInteger('request_status_in_process')->unsigned()->nullable();
            $table->foreign('request_status_in_process')->references('id')->on('merchant_request_statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchant_settings');
    }
}
