<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntityMApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entity_m_application', function (Blueprint $table) {
            $table->unsignedInteger('m_application_id')->index('m_application_id_fk_1488611');
            $table->unsignedInteger('entity_id')->index('entity_id_fk_1488611');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entity_m_application');
    }
}
