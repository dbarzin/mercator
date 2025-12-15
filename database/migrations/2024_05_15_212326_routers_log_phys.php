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
        Schema::table('routers', function (Blueprint $table) {
            $table->string('type')->nullable();
        });

        Schema::create('physical_router_router', function (Blueprint $table) {
            $table->unsignedInteger('router_id')->index('router_id_fk_958343');
            $table->unsignedInteger('physical_router_id')->index('physical_router_id_fk_124983');
            $table->foreign('physical_router_id', 'physical_router_id_fk_124983')->references('id')->on('physical_routers')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('router_id', 'router_id_fk_958343')->references('id')->on('routers')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::create('network_switch_physical_switch', function (Blueprint $table) {
            $table->unsignedInteger('network_switch_id')->index('network_switch_id_fk_543323');
            $table->unsignedInteger('physical_switch_id')->index('physical_switch_id_fk_4543143');
            $table->foreign('network_switch_id', 'network_switch_id_fk_543323')->references('id')->on('network_switches')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('physical_switch_id', 'physical_switch_id_fk_4543143')->references('id')->on('physical_switches')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('routers', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::dropIfExists('physical_router_router');
        Schema::dropIfExists('network_switch_physical_switch');
    }
};
