<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPostMerchantAndPostAlifshopColumnsToApplicationConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_conditions', function (Blueprint $table) {
            $table->boolean('post_merchant')->default(false);
            $table->boolean('post_alifshop')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_conditions', function (Blueprint $table) {
            $table->dropColumn('post_merchant');
            $table->dropColumn('post_alifshop');
        });
    }
}
