<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExternalConnectedEntityNetworkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_connected_entity_network', function (Blueprint $table) {
            $table->unsignedInteger('external_connected_entity_id')->index('external_connected_entity_id_fk_1483344');
            $table->unsignedInteger('network_id')->index('network_id_fk_1483344');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('external_connected_entity_network');
    }
}
