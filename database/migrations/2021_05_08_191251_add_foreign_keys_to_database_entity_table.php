<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToDatabaseEntityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('database_entity', function (Blueprint $table) {
            $table->foreign('database_id', 'database_id_fk_1485563')->references('id')->on('databases')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('entity_id', 'entity_id_fk_1485563')->references('id')->on('entities')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('database_entity', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('database_id_fk_1485563');
            }
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('entity_id_fk_1485563');
            }
        });
    }
}
