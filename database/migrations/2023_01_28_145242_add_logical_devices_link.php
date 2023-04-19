<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLogicalDevicesLink extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('physical_links', function (Blueprint $table) {
            $table->unsignedInteger('router_src_id')->after('workstation_src_id')->nullable()->default(null)->index('router_src_id_fk');
            $table->foreign('router_src_id', 'router_src_id_fk')->references('id')->on('routers')->onUpdate('NO ACTION')->onDelete('CASCADE');

            $table->unsignedInteger('router_dest_id')->after('workstation_dest_id')->nullable()->default(null)->index('router_dest_id_fk');
            $table->foreign('router_dest_id', 'router_dest_id_fk')->references('id')->on('routers')->onUpdate('NO ACTION')->onDelete('CASCADE');

            $table->unsignedInteger('network_switch_src_id')->after('workstation_src_id')->nullable()->default(null)->index('network_switches_src_id_fk');
            $table->foreign('network_switch_src_id', 'network_switch_src_id_fk')->references('id')->on('network_switches')->onUpdate('NO ACTION')->onDelete('CASCADE');

            $table->unsignedInteger('network_switch_dest_id')->after('workstation_dest_id')->nullable()->default(null)->index('network_switches_dest_id_fk');
            $table->foreign('network_switch_dest_id', 'network_switch_dest_id_fk')->references('id')->on('network_switches')->onUpdate('NO ACTION')->onDelete('CASCADE');

            $table->unsignedInteger('logical_server_src_id')->after('workstation_src_id')->nullable()->default(null)->index('logical_server_src_id_fk');
            $table->foreign('logical_server_src_id', 'logical_server_src_id_fk')->references('id')->on('logical_servers')->onUpdate('NO ACTION')->onDelete('CASCADE');

            $table->unsignedInteger('logical_server_dest_id')->after('workstation_dest_id')->nullable()->default(null)->index('logical_server_dest_id_fk');
            $table->foreign('logical_server_dest_id', 'logical_server_dest_id_fk')->references('id')->on('logical_servers')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('physical_links', function (Blueprint $table) {
 
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('router_src_id_fk');
            }
            $table->dropColumn(['router_src_id']);

            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('router_dest_id_fk');
            }
            $table->dropColumn(['router_dest_id']);

            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('network_switch_src_id_fk');
            }
            $table->dropColumn(['network_switch_src_id']);

            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('network_switch_dest_id_fk');
            }
            $table->dropColumn(['network_switch_dest_id']);

            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('logical_server_src_id_fk');
            }
            $table->dropColumn(['logical_server_src_id']);

            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('logical_server_dest_id_fk');
            }
            $table->dropColumn(['logical_server_dest_id']);

        });
    }
}
