<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_uz');
            $table->string('title_ru');
            $table->string('key');
            $table->timestamps();
        });

        DB::table('modules')->insert([
            'title' => 'Azo',
            'title_uz' => 'A\'zo',
            'title_ru' => 'Аъзо',
            'key' => 'azo_merchant'
        ]);

        DB::table('modules')->insert([
            'title' => 'Alifshop',
            'title_uz' => 'Alifshop',
            'title_ru' => 'Алифшоп',
            'key' => 'alifshop_merchant'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
