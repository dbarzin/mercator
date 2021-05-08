<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToLogicalServerMApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logical_server_m_application', function (Blueprint $table) {
            $table->foreign('logical_server_id', 'logical_server_id_fk_1488616')->references('id')->on('logical_servers')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('m_application_id', 'm_application_id_fk_1488616')->references('id')->on('m_applications')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logical_server_m_application', function (Blueprint $table) {
            $table->dropForeign('logical_server_id_fk_1488616');
            $table->dropForeign('m_application_id_fk_1488616');
        });
    }
}
