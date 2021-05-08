<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phones', function (Blueprint $table) {
            $table->foreign('building_id', 'building_fk_1485480')->references('id')->on('buildings')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('physical_switch_id', 'physical_switch_fk_5738332')->references('id')->on('physical_switches')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('site_id', 'site_fk_1485479')->references('id')->on('sites')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('phones', function (Blueprint $table) {
            $table->dropForeign('building_fk_1485480');
            $table->dropForeign('physical_switch_fk_5738332');
            $table->dropForeign('site_fk_1485479');
        });
    }
}
