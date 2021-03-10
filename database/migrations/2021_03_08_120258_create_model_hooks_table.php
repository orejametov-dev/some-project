<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelHooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_hooks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('hookable');

            $table->text('body');
            $table->string('class')->nullable();
            $table->string('action')->nullable();
            $table->string('keyword')->nullable();

            $table->unsignedBigInteger('created_by_id')->nullable();

            $table->unsignedBigInteger('created_from_id')->nullable();
            $table->foreign('created_from_id')->references('id')->on('web_services');

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
        Schema::dropIfExists('model_hooks');
    }
}
