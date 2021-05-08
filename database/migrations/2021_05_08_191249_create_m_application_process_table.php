<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMApplicationProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_application_process', function (Blueprint $table) {
            $table->unsignedInteger('m_application_id')->index('m_application_id_fk_1482573');
            $table->unsignedInteger('process_id')->index('process_id_fk_1482573');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_application_process');
    }
}
