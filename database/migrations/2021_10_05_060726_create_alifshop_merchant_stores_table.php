<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlifshopMerchantStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alifshop_merchant_stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alifshop_merchant_id')->constrained('alifshop_merchants');
            $table->string('name');
            $table->string('address')->nullable();
            $table->text('information')->nullable();

            $table->double('lat')->nullable();
            $table->double('long')->nullable();

            $table->string('region')->nullable();

            $table->boolean('is_main')->default(false);
            $table->boolean('active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alifshop_merchant_stores');
    }
}
