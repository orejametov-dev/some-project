<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCreatedByColumnsOnProblemCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('problem_cases', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable(true)->change();
            $table->string('created_by_name')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('problem_cases', function (Blueprint $table) {
            //
        });
    }
}
