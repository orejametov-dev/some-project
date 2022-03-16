<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePointColumnToProblemCaseTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('problem_case_tags', function (Blueprint $table) {
            $table->float('point')->nullable()->after('type_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('problem_case_tags', function (Blueprint $table) {
            $table->unsignedInteger('point')->nullable()->after('type_id');
        });
    }
}
