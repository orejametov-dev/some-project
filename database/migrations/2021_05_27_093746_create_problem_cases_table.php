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

            $table->string('credit_number')->nullable();
            $table->unsignedBigInteger('application_id')->nullable();

            $table->unsignedBigInteger('client_id')->nullable();

            $table->smallInteger('status_id');
            $table->string('status_key');
            $table->timestamp('status_updated_at');

            $table->unsignedBigInteger('created_by_id');
            $table->string('created_by_name');

            $table->string('created_from_name');

            $table->unsignedBigInteger('assigned_to_id')->nullable();
            $table->string('assigned_to_name')->nullable();
            $table->date('deadline')->nullable();

            $table->text('manager_comment')->nullable();
            $table->text('merchant_comment')->nullable();

            $table->json('application_items');

            $table->string('search_index');

            $table->unsignedBigInteger('engaged_by_id')->nullable();
            $table->string('engaged_by_name')->nullable();
            $table->timestamp('engaged_at')->nullable();

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
