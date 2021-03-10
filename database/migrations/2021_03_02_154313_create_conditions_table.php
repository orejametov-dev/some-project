<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conditions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('merchant_id');
            $table->foreign('merchant_id')->references('id')->on('merchants');

//            $table->unsignedBigInteger('store_id');
//            $table->foreign('store_id')->references('id')->on('stores');

            $table->integer('duration');
            $table->integer('commission');

            $table->string('notice')->nullable();
            $table->boolean('active')->default(false);

            $table->integer('discount');
            $table->boolean('is_promotional')->default(false);

            $table->string('special_offer')->nullable();
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
        Schema::dropIfExists('conditions');
    }
}
