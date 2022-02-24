<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveLegalNameAndLegalNamePrefixToMerchantInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_infos', function (Blueprint $table) {
            $table->dropColumn('legal_name');
            $table->dropColumn('legal_name_prefix');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant_infos', function (Blueprint $table) {
            $table->string('legal_name');
            $table->string('legal_name_prefix')->nullable();
        });
    }
}
