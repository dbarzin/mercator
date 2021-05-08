<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToEntityProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entity_process', function (Blueprint $table) {
            $table->foreign('entity_id', 'entity_id_fk_1627958')->references('id')->on('entities')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('process_id', 'process_id_fk_1627958')->references('id')->on('processes')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entity_process', function (Blueprint $table) {
            $table->dropForeign('entity_id_fk_1627958');
            $table->dropForeign('process_id_fk_1627958');
        });
    }
}
