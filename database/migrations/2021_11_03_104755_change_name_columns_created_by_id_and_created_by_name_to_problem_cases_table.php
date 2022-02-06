<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeNameColumnsCreatedByIdAndCreatedByNameToProblemCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('problem_cases', function (Blueprint $table) {
            $table->renameColumn('created_by_id', 'post_or_pre_created_by_id');
            $table->renameColumn('created_by_name', 'post_or_pre_created_by_name');
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
            $table->renameColumn('post_or_pre_created_by_id', 'created_by_id');
            $table->renameColumn('post_or_pre_created_by_name', 'created_by_name');
        });
    }
}
