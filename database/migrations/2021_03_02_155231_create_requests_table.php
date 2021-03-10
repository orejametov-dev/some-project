<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('information');
            $table->string('legal_name')->nullable(true);

            $table->string('user_name');
            $table->string('user_phone');

            $table->integer('status_id')->unsigned();
            $table->string('region')->nullable();
            $table->unsignedBigInteger('engaged_by_id')->nullable();
            $table->timestamp('engaged_at')->nullable();

            $table->timestamp('status_updated_at');
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
        Schema::dropIfExists('merchant_requests');
    }
}
