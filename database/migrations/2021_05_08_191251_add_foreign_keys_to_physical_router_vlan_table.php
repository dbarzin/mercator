<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPhysicalRouterVlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('physical_router_vlan', function (Blueprint $table) {
            $table->foreign('physical_router_id', 'physical_router_id_fk_1658250')->references('id')->on('physical_routers')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('vlan_id', 'vlan_id_fk_1658250')->references('id')->on('vlans')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('physical_router_vlan', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('physical_router_id_fk_1658250');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('vlan_id_fk_1658250');
            }
        });
    }
}
