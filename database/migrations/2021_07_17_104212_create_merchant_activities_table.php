<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_activities', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('merchant_id');
            $table->foreign('merchant_id')
                ->references('id')
                ->on('merchants');

            $table->unsignedBigInteger('activity_reason_id');
            $table->foreign('activity_reason_id')
                ->references('id')
                ->on('activity_reasons');

            $table->boolean('active');

            $table->unsignedBigInteger('created_by_id');
            $table->string('created_by_name');

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
        Schema::dropIfExists('merchant_activities');
    }
}
