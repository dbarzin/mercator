<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToExternalConnectedEntityNetworkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('external_connected_entity_network', function (Blueprint $table) {
            $table->foreign('external_connected_entity_id', 'external_connected_entity_id_fk_1483344')->references('id')->on('external_connected_entities')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('network_id', 'network_id_fk_1483344')->references('id')->on('networks')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('external_connected_entity_network', function (Blueprint $table) {
            $table->dropForeign('external_connected_entity_id_fk_1483344');
            $table->dropForeign('network_id_fk_1483344');
        });
    }
}
