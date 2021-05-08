<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogicalServerMApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logical_server_m_application', function (Blueprint $table) {
            $table->unsignedInteger('m_application_id')->index('m_application_id_fk_1488616');
            $table->unsignedInteger('logical_server_id')->index('logical_server_id_fk_1488616');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logical_server_m_application');
    }
}
