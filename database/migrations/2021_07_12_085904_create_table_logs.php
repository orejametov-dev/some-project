<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(config('app.env') == 'production') {
            Schema::connection('logs')->create('logs', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->unsignedBigInteger('started_at');
                $table->unsignedBigInteger('finished_at');
                $table->unsignedBigInteger('diff');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('logs')->dropIfExists('logs');
    }
}
