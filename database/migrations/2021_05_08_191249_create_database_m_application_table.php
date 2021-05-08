<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatabaseMApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('database_m_application', function (Blueprint $table) {
            $table->unsignedInteger('m_application_id')->index('m_application_id_fk_1482586');
            $table->unsignedInteger('database_id')->index('database_id_fk_1482586');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('database_m_application');
    }
}
