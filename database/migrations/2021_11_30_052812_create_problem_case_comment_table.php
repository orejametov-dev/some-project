<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProblemCaseCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problem_case_comment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comment_id')->unique();
            $table->string('comment_type')->nullable();
            $table->unsignedBigInteger('problem_case_id');
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
        Schema::dropIfExists('problem_case_comment');
    }
}
