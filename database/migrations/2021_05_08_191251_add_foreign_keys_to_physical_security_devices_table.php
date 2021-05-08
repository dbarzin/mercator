<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPhysicalSecurityDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('physical_security_devices', function (Blueprint $table) {
            $table->foreign('bay_id', 'bay_fk_1485519')->references('id')->on('bays')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('building_id', 'building_fk_1485518')->references('id')->on('buildings')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('site_id', 'site_fk_1485517')->references('id')->on('sites')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('physical_security_devices', function (Blueprint $table) {
            $table->dropForeign('bay_fk_1485519');
            $table->dropForeign('building_fk_1485518');
            $table->dropForeign('site_fk_1485517');
        });
    }
}
