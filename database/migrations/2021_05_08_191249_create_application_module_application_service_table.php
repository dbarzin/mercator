<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationModuleApplicationServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_module_application_service', function (Blueprint $table) {
            $table->unsignedInteger('application_service_id')->index('application_service_id_fk_1492414');
            $table->unsignedInteger('application_module_id')->index('application_module_id_fk_1492414');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_module_application_service');
    }
}
