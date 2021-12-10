<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewClientsDataColumnsToProblemCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('problem_cases', function (Blueprint $table) {
            $table->string('client_name');
            $table->string('client_surname');
            $table->string('client_patronymic');
            $table->string('phone');
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
            $table->dropColumn('client_name');
            $table->dropColumn('client_surname');
            $table->dropColumn('client_patronymic');
            $table->dropColumn('phone');
        });
    }
}
