<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_requests', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->text('information');
            $table->string('legal_name')->nullable(true);

            $table->string('user_name');
            $table->string('user_phone');

            $table->bigInteger('status_id')->unsigned();

            $table->string('region')->nullable();

            $table->unsignedBigInteger('engaged_by_id')->nullable();
            $table->string('engaged_by_name')->nullable();
            $table->timestamp('engaged_at')->nullable();

            $table->timestamps();
            $table->timestamp('status_updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchant_requests');
    }
}
