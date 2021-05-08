<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToOperationTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('operation_task', function (Blueprint $table) {
            $table->foreign('operation_id', 'operation_id_fk_1472749')->references('id')->on('operations')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('task_id', 'task_id_fk_1472749')->references('id')->on('tasks')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('operation_task', function (Blueprint $table) {
            $table->dropForeign('operation_id_fk_1472749');
            $table->dropForeign('task_id_fk_1472749');
        });
    }
}
