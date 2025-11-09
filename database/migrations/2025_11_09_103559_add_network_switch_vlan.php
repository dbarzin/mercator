<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the network_switch_vlan pivot table mapping network switches to VLANs.
     *
     * The table contains unsigned integer columns `network_switch_id` and `vlan_id`,
     * a composite primary key on (`network_switch_id`, `vlan_id`) to prevent duplicates,
     * individual indexes on each column, and foreign key constraints referencing
     * `network_switches.id` and `vlans.id` with cascade on delete.
     */
    public function up(): void
    {

        Schema::create('network_switch_vlan', function (Blueprint $table) {
            $table->unsignedInteger('network_switch_id');
            $table->unsignedInteger('vlan_id');

            $table->primary(['network_switch_id', 'vlan_id']); // empêche les doublons
            $table->index('network_switch_id');
            $table->index('vlan_id');

            $table->foreign('network_switch_id')
                ->references('id')->on('network_switches')
                ->onDelete('cascade');

            $table->foreign('vlan_id')
                ->references('id')->on('vlans')
                ->onDelete('cascade');
        });
    }

    /**
     * Drop the network_switch_vlan pivot table if it exists.
     */
    public function down(): void
    {
        Schema::dropIfExists('network_switch_vlan');
    }
};