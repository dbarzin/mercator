<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ApplicationWorkstation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_application_workstation', function (Blueprint $table) {
            $table->unsignedInteger('m_application_id')->index('m_application_id_fk_1486547');
            $table->unsignedInteger('workstation_id')->index('workstation_id_fk_1486547');
            $table->foreign('workstation_id', 'workstation_id_fk_1486547')->references('id')->on('workstations')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('m_application_id', 'm_application_id_fk_1486547')->references('id')->on('m_applications')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('workstations', function (Blueprint $table) {
            $table->string('operating_system')->nullable();
            $table->string('address_ip')->nullable();
            $table->string('cpu')->nullable();
            $table->string('memory')->nullable();
            $table->integer('disk')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_application_workstation');

        Schema::table('workstations', function (Blueprint $table) {
            $table->dropColumn('operating_system');
            $table->dropColumn('address_ip');
            $table->dropColumn('cpu');
            $table->dropColumn('memory');
            $table->dropColumn('disk');
        });
    }
}
