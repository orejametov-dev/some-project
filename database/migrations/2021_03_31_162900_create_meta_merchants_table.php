<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetaMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meta_merchants', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sales_amount_monthly')->unsigned()->default(0);
            $table->integer('sales_percentage_monthly')->unsigned()->default(0);
            $table->bigInteger('sales_amount_total')->unsigned()->default(0);
            $table->integer('sales_percentage_total')->unsigned()->default(0);

            $table->boolean('sales_trend')->nullable();

            $table->unsignedBigInteger('merchant_id')->unique();
            $table->foreign('merchant_id')->references('id')->on('merchants');

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
        Schema::dropIfExists('meta_merchants');
    }
}
