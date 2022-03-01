<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewCreatedFromNameColumnToMerchantRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_requests', function (Blueprint $table) {
            $table->string('created_from_name')->after('legal_name_prefix');
            $table->unsignedBigInteger('approximate_sales')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant_requests', function (Blueprint $table) {
            $table->dropColumn('created_from_name');
        });
    }
}
