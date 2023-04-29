<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToWorkstationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workstations', function (Blueprint $table) {
            $table->foreign('building_id', 'building_fk_1485333')->references('id')->on('buildings')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('physical_switch_id', 'physical_switch_fk_0938434')->references('id')->on('physical_switches')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('site_id', 'site_fk_1485332')->references('id')->on('sites')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workstations', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('building_fk_1485333');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('physical_switch_fk_0938434');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('site_fk_1485332');
            }
        });
    }
}
