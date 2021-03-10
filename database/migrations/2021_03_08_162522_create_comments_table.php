<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->text('body');
            $table->morphs('commentable');

            $table->bigInteger('created_from_id')->unsigned();
            $table->foreign('created_from_id')->references('id')->on('web_services');
            $table->bigInteger('updated_from_id')->unsigned();
            $table->foreign('updated_from_id')->references('id')->on('web_services');

            $table->bigInteger('created_by_id')->unsigned();
            $table->bigInteger('updated_by_id')->unsigned();

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
        Schema::dropIfExists('comments');
    }
}
