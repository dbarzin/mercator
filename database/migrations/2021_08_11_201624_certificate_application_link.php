<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CertificateApplicationLink extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add table between certificate and applications
        Schema::create('certificate_m_application', function (Blueprint $table) {
            $table->unsignedInteger('certificate_id')->index('certificate_id_fk_4584393');
            $table->unsignedInteger('m_application_id')->index('m_application_id_fk_4584393s');

            $table->foreign('certificate_id')->references('id')->on('certificates')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('m_application_id')->references('id')->on('m_applications')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // drop table
        Schema::dropIfExists('certificate_m_application');
    }
}
