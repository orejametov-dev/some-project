<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProblemCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problem_cases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('merchant_id');
            $table->foreign('merchant_id')->references('id')->on('merchants');

            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores');

            $table->smallInteger('status_id');
            $table->string('status_key');

            $table->unsignedBigInteger('tag_id');
            $table->foreign('tag_id')->references('id')->on('problem_case_tags');

            $table->unsignedBigInteger('created_by_id');
            $table->string('created_by_name');

            $table->string('created_from_name');

            $table->string('created_number');
            $table->unsignedBigInteger('application_id');

            $table->json('application_items');
            $table->string('good_type');

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
        Schema::dropIfExists('problem_cases');
    }
}
