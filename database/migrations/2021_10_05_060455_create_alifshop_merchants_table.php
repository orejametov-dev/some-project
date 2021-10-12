<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlifshopMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alifshop_merchants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies');
            $table->string('name')->unique();
            $table->string('legal_name')->nullable();
            $table->string('information')->nullable();
            $table->string('token')->nullable();
            $table->string('alifshop_slug');
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('maintainer_id')->nullable();
            $table->string('logo_url')->nullable();
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
        Schema::dropIfExists('alifshop_merchants');
    }
}
