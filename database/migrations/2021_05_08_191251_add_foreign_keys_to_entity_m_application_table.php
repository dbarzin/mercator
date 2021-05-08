<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToEntityMApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entity_m_application', function (Blueprint $table) {
            $table->foreign('entity_id', 'entity_id_fk_1488611')->references('id')->on('entities')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('m_application_id', 'm_application_id_fk_1488611')->references('id')->on('m_applications')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entity_m_application', function (Blueprint $table) {
            $table->dropForeign('entity_id_fk_1488611');
            $table->dropForeign('m_application_id_fk_1488611');
        });
    }
}
