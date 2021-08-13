<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePermissionsOfMerchantUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_users', function (Blueprint $table) {
            $table->dropColumn('permission_applications');
            $table->dropColumn('permission_deliveries');
            $table->dropColumn('permission_manager');
            $table->dropColumn('permission_upload_goods');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant_users', function (Blueprint $table) {
            $table->boolean('permission_applications')->default(false);
            $table->boolean('permission_deliveries')->default(false);
            $table->boolean('permission_manager')->default(false);
            $table->boolean('permission_upload_goods')->default(false);
            $table->boolean('permission_oso')->default(false);
            $table->boolean('permission_orders')->default(false);
        });
    }
}
