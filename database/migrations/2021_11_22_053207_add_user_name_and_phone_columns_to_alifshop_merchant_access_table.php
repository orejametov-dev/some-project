<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserNameAndPhoneColumnsToAlifshopMerchantAccessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alifshop_merchant_accesses', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('store_id')->unique()->nullable();
            $table->string('user_name')->after('user_id')->nullable();
            $table->string('phone')->nullable()->after('user_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alifshop_merchant_accesses', function (Blueprint $table) {
            //
        });
    }
}
