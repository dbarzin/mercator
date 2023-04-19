<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVpnFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('external_connected_entities', function (Blueprint $table) {
            $table->longText('description')->nullable()->after("name");

            $table->string("type")->nullable()->after("description");
            $table->dropColumn("responsible_sec");

            $table->unsignedInteger('entity_id')->after('type')->nullable()->index('entity_id_fk_1295034');
            $table->foreign('entity_id', 'entity_id_fk_1295034')->references('id')->on('entities')->onUpdate('NO ACTION')->onDelete('CASCADE');

            $table->unsignedInteger('network_id')->after('entity_id')->nullable()->index('network_id_fk_8596554');
            $table->foreign('network_id', 'network_id_fk_8596554')->references('id')->on('networks')->onUpdate('NO ACTION')->onDelete('CASCADE');

            $table->string("src")->nullable()->after("network_id");
            $table->string("dest")->nullable()->after("src");
        });
        Schema::dropIfExists('external_connected_entity_network');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('external_connected_entities', function (Blueprint $table) {
            $table->dropColumn(['description', 'type']);
            $table->string("responsible_sec")->nullable()->after("name");

            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('network_id_fk_8596554');
            }
            $table->dropColumn(['network_id']);

            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('entity_id_fk_1295034');
            }
            $table->dropColumn(['entity_id']);

            $table->dropColumn(['src', 'dest']);
        });
        Schema::create('external_connected_entity_network', function (Blueprint $table) {
            $table->unsignedInteger('external_connected_entity_id')->index('external_connected_entity_id_fk_1483344');
            $table->unsignedInteger('network_id')->index('network_id_fk_1483344');
        });
    }
}



