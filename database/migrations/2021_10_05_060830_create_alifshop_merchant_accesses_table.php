<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlifshopMerchantAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alifshop_merchant_accesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_user_id')->constrained('company_users');
            $table->foreignId('alifshop_merchant_id')->constrained('alifshop_merchants');
            $table->foreignId('alifshop_merchant_store_id')->constrained('alifshop_merchant_stores');

            $table->unsignedBigInteger('user_id')->unique();
            $table->string('user_name');
            $table->string('phone')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alifshop_merchant_accesses');
    }
}
