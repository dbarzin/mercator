<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // link security_control <-> m_application
        Schema::create('security_control_m_application', function (Blueprint $table) {
            $table->unsignedInteger('security_control_id')->index('security_control_id_fk_5920381');
            $table->unsignedInteger('m_application_id')->index('m_application_id_fk_5837573');
        });

        Schema::table('security_control_m_application', function (Blueprint $table) {
            $table->foreign('security_control_id', 'security_control_id_fk_49294573')->references('id')->on('security_controls')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('m_application_id', 'm_application_id_fk_304958543')->references('id')->on('m_applications')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        // link security_control <-> process
        Schema::create('security_control_process', function (Blueprint $table) {
            $table->unsignedInteger('security_control_id')->index('security_control_id_fk_54354353');
            $table->unsignedInteger('process_id')->index('process_id_fk_5837573');
        });

        Schema::table('security_control_process', function (Blueprint $table) {
            $table->foreign('security_control_id', 'security_control_id_fk_54354354')->references('id')->on('security_controls')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('process_id', 'process_id_fk_49485754')->references('id')->on('processes')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // delete tables
        Schema::dropIfExists('security_control_m_application');
        Schema::dropIfExists('security_control_process');
    }
};
