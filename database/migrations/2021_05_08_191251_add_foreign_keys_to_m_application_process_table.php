<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMApplicationProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_application_process', function (Blueprint $table) {
            $table->foreign('m_application_id', 'm_application_id_fk_1482573')->references('id')->on('m_applications')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('process_id', 'process_id_fk_1482573')->references('id')->on('processes')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_application_process', function (Blueprint $table) {
            $table->dropForeign('m_application_id_fk_1482573');
            $table->dropForeign('process_id_fk_1482573');
        });
    }
}
