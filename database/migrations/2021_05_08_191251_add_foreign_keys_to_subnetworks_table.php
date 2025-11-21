<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSubnetworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subnetworks', function (Blueprint $table) {
            $table->foreign('connected_subnets_id', 'connected_subnets_fk_1483256')->references('id')->on('subnetworks')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('gateway_id', 'gateway_fk_1492376')->references('id')->on('gateways')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subnetworks', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('connected_subnets_fk_1483256');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('gateway_fk_1492376');
            }
        });
    }
}
