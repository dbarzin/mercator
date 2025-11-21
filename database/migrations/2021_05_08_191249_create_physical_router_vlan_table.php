<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhysicalRouterVlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('physical_router_vlan', function (Blueprint $table) {
            $table->unsignedInteger('physical_router_id')->index('physical_router_id_fk_1658250');
            $table->unsignedInteger('vlan_id')->index('vlan_id_fk_1658250');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('physical_router_vlan');
    }
}
