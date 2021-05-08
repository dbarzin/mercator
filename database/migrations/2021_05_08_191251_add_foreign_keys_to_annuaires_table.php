<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAnnuairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('annuaires', function (Blueprint $table) {
            $table->foreign('zone_admin_id', 'zone_admin_fk_1482666')->references('id')->on('zone_admins')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('annuaires', function (Blueprint $table) {
            $table->dropForeign('zone_admin_fk_1482666');
        });
    }
}
