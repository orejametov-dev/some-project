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

            $table->unsignedBigInteger('merchant_id');
            $table->foreign('merchant_id')->references('id')->on('merchants');

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

            $table->date('contact_date');

            $table->unsignedBigInteger('limit')->default(100000000000);
            $table->date('limit_expired_at')->nullable();
            $table->unsignedBigInteger('rest_limit')->default(0);

            $table->timestamps();
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
