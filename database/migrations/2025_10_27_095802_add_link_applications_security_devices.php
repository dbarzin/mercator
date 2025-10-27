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
    Schema::create('m_application_security_device', function (Blueprint $table) {
        $table->unsignedInteger('security_device_id')->index('security_device_id_fk_304832731');
        $table->unsignedInteger('m_application_id')->index('m_application_id_fk_41923483');
        });

    Schema::table('m_application_security_device', function (Blueprint $table) {
            $table->foreign('security_device_id', 'security_device_id_fk_304832731')->references('id')->on('security_devices')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('m_application_id', 'm_application_id_fk_41923483')->references('id')->on('m_applications')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

        /**
         * Reverse the migrations.
         */
        public function down(): void
    {
        //
        Schema::dropIfExists('m_application_security_device');
    }
};
