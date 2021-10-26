<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('module_id')->constrained('modules');
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique(['company_id', 'module_id']);
        });

        $merchants = DB::table('merchants')->get(['id', 'company_id', 'active']);

        foreach ($merchants as $merchant) {
            DB::table('company_modules')->insert([
                'company_id' => $merchant->company_id,
                'module_id' => \App\Modules\Companies\Models\Module::AZO_MERCHANT,
                'active' => $merchant->active
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_modules');
    }
}
