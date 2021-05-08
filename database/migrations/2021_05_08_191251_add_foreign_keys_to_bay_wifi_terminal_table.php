<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBayWifiTerminalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bay_wifi_terminal', function (Blueprint $table) {
            $table->foreign('bay_id', 'bay_id_fk_1485509')->references('id')->on('bays')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('wifi_terminal_id', 'wifi_terminal_id_fk_1485509')->references('id')->on('wifi_terminals')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bay_wifi_terminal', function (Blueprint $table) {
            $table->dropForeign('bay_id_fk_1485509');
            $table->dropForeign('wifi_terminal_id_fk_1485509');
        });
    }
}
