<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToDatabaseInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('database_information', function (Blueprint $table) {
            $table->foreign('database_id', 'database_id_fk_1485570')->references('id')->on('databases')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('information_id', 'information_id_fk_1485570')->references('id')->on('information')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('database_information', function (Blueprint $table) {
            $table->dropForeign('database_id_fk_1485570');
            $table->dropForeign('information_id_fk_1485570');
        });
    }
}
