<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPhysicalRoutersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('physical_routers', function (Blueprint $table) {
            $table->foreign('bay_id', 'bay_fk_1485499')->references('id')->on('bays')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('building_id', 'building_fk_1485498')->references('id')->on('buildings')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('site_id', 'site_fk_1485497')->references('id')->on('sites')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('physical_routers', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('bay_fk_1485499');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('building_fk_1485498');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('site_fk_1485497');
            }
        });
    }
}
