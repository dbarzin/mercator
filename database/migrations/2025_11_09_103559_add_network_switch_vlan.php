<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
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
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('network_switch_vlan');
    }
};
