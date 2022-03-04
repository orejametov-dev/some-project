<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnsToMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchants', function (Blueprint $table) {
            $table->dropColumn('alifshop_slug');
            $table->dropColumn('information');
            $table->dropColumn('telegram_chat_id');
            $table->dropColumn('paymo_terminal_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchants', function (Blueprint $table) {
            $table->string('information')->nullable();
            $table->string('alifshop_slug');
            $table->string('telegram_chat_id')->nullable();
        });
    }
}
