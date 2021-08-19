<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NetworkRedesign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subnetworks', function(Blueprint $table) {
            // add column zone in subnetworks
            $table->string('zone')->nullable();
            // add link to vlan in subnetwork
            $table->unsignedInteger('vlan_id')->nullable()->index('vlan_fk_6844934');
            $table->foreign('vlan_id', 'vlan_fk_6844934')->references('id')->on('vlans')->onUpdate('NO ACTION')->onDelete('NO ACTION');        
            // add link to network in subnetwork
            $table->unsignedInteger('network_id')->nullable()->index('network_fk_5476544');
            $table->foreign('network_id', 'network_fk_5476544')->references('id')->on('networks')->onUpdate('NO ACTION')->onDelete('NO ACTION');        
        });

        /* to process later...
        // remove link to physical_switch_id in wifi_terminals
        Schema::table('wifi_terminals', function(Blueprint $table) {
            $table->dropColumn('physical_switch_id');
        }

        // cleanup VLAN table
        Schema::table('vlans', function(Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('mask');
            $table->dropColumn('gateway');
            $table->dropColumn('zone');
        }

        // remove table between networks and subnetworks
        Schema::dropTable('network_subnetword');
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subnetworks', function(Blueprint $table) {
            // remove column zone in subnetworks
            $table->dropColumn('zone');
            // remove link to vlan in subnetwork
            $table->dropForeign('vlan_fk_6844934');
            $table->dropColumn('vlan_id');
            // remove link to vlan in network
            $table->dropForeign('network_fk_5476544');
            $table->dropColumn('network_id');
        });
        }
}
