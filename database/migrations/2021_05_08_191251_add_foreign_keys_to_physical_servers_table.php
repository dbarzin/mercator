<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPhysicalServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('physical_servers', function (Blueprint $table) {
            $table->foreign('bay_id', 'bay_fk_1485324')->references('id')->on('bays')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('building_id', 'building_fk_1485323')->references('id')->on('buildings')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('physical_switch_id', 'physical_switch_fk_8732342')->references('id')->on('physical_switches')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('site_id', 'site_fk_1485322')->references('id')->on('sites')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('physical_servers', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('bay_fk_1485324');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('building_fk_1485323');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('physical_switch_fk_8732342');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('site_fk_1485322');
            }
        });
    }
}
