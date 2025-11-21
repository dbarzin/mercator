<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNetworkSubnetwordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('network_subnetword', function (Blueprint $table) {
            $table->unsignedInteger('network_id')->index('network_id_fk_1492377');
            $table->unsignedInteger('subnetword_id')->index('subnetword_id_fk_1492377');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('network_subnetword');
    }
}
