<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToDatabaseMApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('database_m_application', function (Blueprint $table) {
            $table->foreign('database_id', 'database_id_fk_1482586')->references('id')->on('databases')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('m_application_id', 'm_application_id_fk_1482586')->references('id')->on('m_applications')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('database_m_application', function (Blueprint $table) {
            $table->dropForeign('database_id_fk_1482586');
            $table->dropForeign('m_application_id_fk_1482586');
        });
    }
}
