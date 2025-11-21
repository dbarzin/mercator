<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationServiceMApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_service_m_application', function (Blueprint $table) {
            $table->unsignedInteger('m_application_id')->index('m_application_id_fk_1482585');
            $table->unsignedInteger('application_service_id')->index('application_service_id_fk_1482585');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_service_m_application');
    }
}
