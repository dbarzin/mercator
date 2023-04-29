<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToActivityProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activity_process', function (Blueprint $table) {
            $table->foreign('activity_id', 'activity_id_fk_1627616')->references('id')->on('activities')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('process_id', 'process_id_fk_1627616')->references('id')->on('processes')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activity_process', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('activity_id_fk_1627616');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('process_id_fk_1627616');
            }
        });
    }
}
