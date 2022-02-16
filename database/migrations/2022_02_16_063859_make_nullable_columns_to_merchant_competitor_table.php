<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeNullableColumnsToMerchantCompetitorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_competitor', function (Blueprint $table) {
            $table->integer('volume_sales')->nullable()->change();
            $table->integer('percentage_approve')->nullable()->change();
            $table->dateTime('partnership_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant_competitor', function (Blueprint $table) {
            $table->bigInteger('volume_sales');
            $table->integer('percentage_approve');
            $table->timestamp('partnership_at');
        });
    }
}
