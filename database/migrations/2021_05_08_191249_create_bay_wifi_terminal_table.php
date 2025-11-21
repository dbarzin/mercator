<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBayWifiTerminalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bay_wifi_terminal', function (Blueprint $table) {
            $table->unsignedInteger('wifi_terminal_id')->index('wifi_terminal_id_fk_1485509');
            $table->unsignedInteger('bay_id')->index('bay_id_fk_1485509');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bay_wifi_terminal');
    }
}
