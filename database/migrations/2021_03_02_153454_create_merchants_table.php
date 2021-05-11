<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('legal_name')->nullable();
            $table->string('information')->nullable();
            $table->string('token')->nullable();
            $table->string('alifshop_slug');
            $table->string('telegram_chat_id')->nullable();

            $table->boolean('has_deliveries')->default(false);
            $table->boolean('has_manager')->default(false);
            $table->boolean('has_applications')->default(false);
            $table->boolean('has_orders')->default(false);
            $table->string('logo_url')->nullable();

            $table->unsignedBigInteger('maintainer_id')->nullable();
            $table->unsignedBigInteger('current_sales')->nullable();

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
        Schema::dropIfExists('merchants');
    }
}
