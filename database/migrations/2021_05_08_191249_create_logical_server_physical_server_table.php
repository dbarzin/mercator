<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogicalServerPhysicalServerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logical_server_physical_server', function (Blueprint $table) {
            $table->unsignedInteger('logical_server_id')->index('logical_server_id_fk_1657961');
            $table->unsignedInteger('physical_server_id')->index('physical_server_id_fk_1657961');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logical_server_physical_server');
    }
}
