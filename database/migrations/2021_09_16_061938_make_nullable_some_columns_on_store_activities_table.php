<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeNullableSomeColumnsOnStoreActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_activities', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable()->change();
            $table->string('created_by_name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_activities', function (Blueprint $table) {
            //
        });
    }
}
