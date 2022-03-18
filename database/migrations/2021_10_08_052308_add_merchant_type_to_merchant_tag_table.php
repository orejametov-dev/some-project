<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMerchantTypeToMerchantTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_tag', function (Blueprint $table) {
            $table->string('merchant_type')
                ->after('merchant_id')
                ->nullable();

            $table->unique(['merchant_id', 'merchant_type', 'tag_id']);
        });

        DB::table('merchant_tag')->update([
           'merchant_type' => \App\Models\Merchant::class,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant_tag', function (Blueprint $table) {
            $table->dropColumn('merchant_type');
            $table->dropUnique(['merchant_id', 'merchant_type', 'tag_id']);
        });
    }
}
