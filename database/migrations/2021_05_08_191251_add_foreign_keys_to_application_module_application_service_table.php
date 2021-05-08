<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToApplicationModuleApplicationServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_module_application_service', function (Blueprint $table) {
            $table->foreign('application_module_id', 'application_module_id_fk_1492414')->references('id')->on('application_modules')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('application_service_id', 'application_service_id_fk_1492414')->references('id')->on('application_services')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_module_application_service', function (Blueprint $table) {
            $table->dropForeign('application_module_id_fk_1492414');
            $table->dropForeign('application_service_id_fk_1492414');
        });
    }
}
