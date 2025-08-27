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
        // link security_device <-> physical_security_device

        Schema::create('physical_security_device_security_device', function (Blueprint $table) {
            $table->unsignedInteger('security_device_id')->index('security_device_id_fk_43329392');
            $table->unsignedInteger('physical_security_device_id')->index('physical_security_device_id_fk_6549543');
        });

        Schema::table('physical_security_device_security_device', function (Blueprint $table) {
            $table->foreign('security_device_id', 'security_device_id_fk_43329392')->references('id')->on('security_devices')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('physical_security_device_id', 'physical_security_device_id_fk_6549543')->references('id')->on('physical_security_devices')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('physical_security_device_security_device');
    }
};
