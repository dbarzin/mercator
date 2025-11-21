<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToWifiTerminalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wifi_terminals', function (Blueprint $table) {
            $table->foreign('building_id', 'building_fk_1485508')->references('id')->on('buildings')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('physical_switch_id', 'physical_switch_fk_593584')->references('id')->on('physical_switches')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('site_id', 'site_fk_1485507')->references('id')->on('sites')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wifi_terminals', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('building_fk_1485508');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('physical_switch_fk_593584');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('site_fk_1485507');
            }
        });
    }
}
