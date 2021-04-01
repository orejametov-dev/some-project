<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantActiveNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_active_notes', function (Blueprint $table) {
            $table->id();
            $table->text('body');

            $table->unsignedBigInteger('merchant_id')->unique();
            $table->foreign('merchant_id')->references('id')->on('merchants');
            $table->string('created_by_str');
            $table->string('updated_by_str');
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
        Schema::dropIfExists('merchant_active_notes');
    }
}
