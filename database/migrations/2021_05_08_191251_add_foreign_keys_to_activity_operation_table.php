<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToActivityOperationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activity_operation', function (Blueprint $table) {
            $table->foreign('activity_id', 'activity_id_fk_1472704')->references('id')->on('activities')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('operation_id', 'operation_id_fk_1472704')->references('id')->on('operations')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activity_operation', function (Blueprint $table) {
            $table->dropForeign('activity_id_fk_1472704');
            $table->dropForeign('operation_id_fk_1472704');
        });
    }
}
