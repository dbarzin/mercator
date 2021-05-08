<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToApplicationServiceMApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_service_m_application', function (Blueprint $table) {
            $table->foreign('application_service_id', 'application_service_id_fk_1482585')->references('id')->on('application_services')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('m_application_id', 'm_application_id_fk_1482585')->references('id')->on('m_applications')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_service_m_application', function (Blueprint $table) {
            $table->dropForeign('application_service_id_fk_1482585');
            $table->dropForeign('m_application_id_fk_1482585');
        });
    }
}
