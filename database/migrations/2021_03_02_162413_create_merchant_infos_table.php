<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_infos', function (Blueprint $table) {
            $table->id();

            $table->string('legal_name');
            $table->string('director_name');
            $table->string('phone');
            $table->string('vat_number');
            $table->string('mfo');
            $table->string('tin');
            $table->string('oked');
            $table->text('address');
            $table->string('bank_account');
            $table->string('bank_name');
            $table->integer('contract_number');

            $table->unsignedBigInteger('merchant_id')->unique();
            $table->foreign('merchant_id')->references('id')->on('merchants');

            $table->bigInteger('limit')->nullable();
            $table->dateTime('limit_expired_at')->nullable();

            $table->dateTime('contract_date')->nullable();

            $table->bigInteger('rest_limit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchant_infos');
    }
}
