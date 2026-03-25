<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Cleanup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vlans', function (Blueprint $table) {
            $table->dropColumn(['address', 'mask', 'zone', 'gateway']);
        });

        Schema::table('subnetworks', function (Blueprint $table) {
            $table->dropColumn(['ip_range']);
        });

        Schema::table('wifi_terminals', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('physical_switch_fk_593584');
                $table->dropColumn(['physical_switch_id']);
            }
        });

        Schema::drop('network_subnetwork');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('vlans', function (Blueprint $table) {
            $table->char('address')->nullable();
            $table->char('mask')->nullable();
            $table->char('zone')->nullable();
            $table->char('gateway')->nullable();
        });

        Schema::table('wifi_terminals', function (Blueprint $table) {
            $table->unsignedInteger('physical_switch_id')->nullable()->index('physical_switch_fk_593584');
            $table->foreign('physical_switch_id', 'physical_switch_fk_593584')->references('id')->on('physical_switches')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('subnetworks', function (Blueprint $table) {
            $table->char('ip_range')->nullable();
        });

        Schema::create('network_subnetwork', function (Blueprint $table) {
            $table->unsignedInteger('network_id')->index('network_id_fk_1492377');
            $table->unsignedInteger('subnetword_id')->index('subnetword_id_fk_1492377');
        });
    }
}
