<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToActorOperationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('actor_operation', function (Blueprint $table) {
            $table->foreign('actor_id', 'actor_id_fk_1472680')->references('id')->on('actors')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('operation_id', 'operation_id_fk_1472680')->references('id')->on('operations')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('actor_operation', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('actor_id_fk_1472680');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('operation_id_fk_1472680');
            }
        });
    }
}
