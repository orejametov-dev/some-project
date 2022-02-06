<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveIsAzoAndIsAlifshopColumnsToStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('is_azo');
            $table->dropColumn('is_alifshop');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->boolean('is_azo')->default(false);
            $table->boolean('is_alifshop')->default(false);
            $table->unique(['name']);
        });

        DB::table('stores')->update([
            'is_azo' => true,
        ]);
    }
}
