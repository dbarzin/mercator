<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPeripheralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('peripherals', function (Blueprint $table) {
            $table->foreign('bay_id', 'bay_fk_1485451')->references('id')->on('bays')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('building_id', 'building_fk_1485450')->references('id')->on('buildings')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('site_id', 'site_fk_1485449')->references('id')->on('sites')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('peripherals', function (Blueprint $table) {
            $table->dropForeign('bay_fk_1485451');
            $table->dropForeign('building_fk_1485450');
            $table->dropForeign('site_fk_1485449');
        });
    }
}
