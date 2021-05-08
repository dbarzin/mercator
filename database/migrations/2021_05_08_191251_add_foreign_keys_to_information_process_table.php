<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToInformationProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('information_process', function (Blueprint $table) {
            $table->foreign('information_id', 'information_id_fk_1473025')->references('id')->on('information')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('process_id', 'process_id_fk_1473025')->references('id')->on('processes')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('information_process', function (Blueprint $table) {
            $table->dropForeign('information_id_fk_1473025');
            $table->dropForeign('process_id_fk_1473025');
        });
    }
}
