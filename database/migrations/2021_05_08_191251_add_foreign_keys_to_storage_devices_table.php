<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToStorageDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('storage_devices', function (Blueprint $table) {
            $table->foreign('bay_id', 'bay_fk_1485363')->references('id')->on('bays')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('building_id', 'building_fk_1485362')->references('id')->on('buildings')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('physical_switch_id', 'physical_switch_fk_4025543')->references('id')->on('physical_switches')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('site_id', 'site_fk_1485361')->references('id')->on('sites')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('storage_devices', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('bay_fk_1485363');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('building_fk_1485362');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('physical_switch_fk_4025543');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('site_fk_1485361');
            }
        });
    }
}
