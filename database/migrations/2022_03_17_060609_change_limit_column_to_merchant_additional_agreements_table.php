<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLimitColumnToMerchantAdditionalAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_additional_agreements', function (Blueprint $table) {
            $table->bigInteger('limit')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant_additional_agreements', function (Blueprint $table) {
            $table->bigInteger('limit');
        });
    }
}
