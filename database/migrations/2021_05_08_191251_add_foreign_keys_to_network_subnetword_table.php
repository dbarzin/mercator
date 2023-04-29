<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToNetworkSubnetwordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('network_subnetword', function (Blueprint $table) {
            $table->foreign('network_id', 'network_id_fk_1492377')->references('id')->on('networks')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('subnetword_id', 'subnetword_id_fk_1492377')->references('id')->on('subnetworks')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('network_subnetword', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('network_id_fk_1492377');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('subnetword_id_fk_1492377');
            }
        });
    }
}
