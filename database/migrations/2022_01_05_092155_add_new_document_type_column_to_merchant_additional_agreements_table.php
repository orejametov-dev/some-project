<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewDocumentTypeColumnToMerchantAdditionalAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_additional_agreements', function (Blueprint $table) {
            $table->string('document_type');
        });

        DB::table('merchant_additional_agreements')
            ->update(['document_type' => 'old']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant_additional_agreements', function (Blueprint $table) {
            $table->dropColumn('document_type');
        });
    }
}
