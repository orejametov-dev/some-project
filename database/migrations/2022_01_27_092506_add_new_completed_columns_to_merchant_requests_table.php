<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewCompletedColumnsToMerchantRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_requests', function (Blueprint $table) {
            $table->boolean('main_completed')->default(false);
            $table->boolean('documents_completed')->default(false);
            $table->boolean('file_completed')->default(false);
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
            $table->dropColumn('main_completed');
            $table->dropColumn('documents_completed');
            $table->dropColumn('file_completed');

        });
    }
}
