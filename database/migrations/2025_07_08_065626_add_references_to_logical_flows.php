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
        Schema::table('logical_flows', function (Blueprint $table) {
            $table->string('source_ip_range')->nullable()->change();
            $table->string('dest_ip_range')->nullable()->change();

            // Sources
            $table->unsignedInteger('logical_server_source_id')->nullable();
            $table->foreign('logical_server_source_id')
                ->references('id')
                ->on('logical_servers')
                ->onDelete('cascade');

            $table->unsignedInteger('peripheral_source_id')->nullable();
            $table->foreign('peripheral_source_id')
                ->references('id')
                ->on('peripherals')
                ->onDelete('cascade');

            $table->unsignedInteger('physical_server_source_id')->nullable();
            $table->foreign('physical_server_source_id')
                ->references('id')
                ->on('physical_servers')
                ->onDelete('cascade');

            $table->unsignedInteger('storage_device_source_id')->nullable();
            $table->foreign('storage_device_source_id')
                ->references('id')
                ->on('storage_devices')
                ->onDelete('cascade');

            $table->unsignedInteger('workstation_source_id')->nullable();
            $table->foreign('workstation_source_id')
                ->references('id')
                ->on('workstations')
                ->onDelete('cascade');

            $table->unsignedInteger('physical_security_device_source_id')->nullable();
            $table->foreign('physical_security_device_source_id')
                ->references('id')
                ->on('physical_security_devices')
                ->onDelete('cascade');

            // Destinations
            $table->unsignedInteger('logical_server_dest_id')->nullable();
            $table->foreign('logical_server_dest_id')
                ->references('id')
                ->on('logical_servers')
                ->onDelete('cascade');

            $table->unsignedInteger('peripheral_dest_id')->nullable();
            $table->foreign('peripheral_dest_id')
                ->references('id')
                ->on('peripherals')
                ->onDelete('cascade');

            $table->unsignedInteger('physical_server_dest_id')->nullable();
            $table->foreign('physical_server_dest_id')
                ->references('id')
                ->on('physical_servers')
                ->onDelete('cascade');

            $table->unsignedInteger('storage_device_dest_id')->nullable();
            $table->foreign('storage_device_dest_id')
                ->references('id')
                ->on('storage_devices')
                ->onDelete('cascade');

            $table->unsignedInteger('workstation_dest_id')->nullable();
            $table->foreign('workstation_dest_id')
                ->references('id')
                ->on('workstations')
                ->onDelete('cascade');

            $table->unsignedInteger('physical_security_device_dest_id')->nullable();
            $table->foreign('physical_security_device_dest_id')
                ->references('id')
                ->on('physical_security_devices')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logical_flows', function (Blueprint $table) {
            $table->dropForeign(['logical_server_source_id']);
            $table->dropForeign(['peripheral_source_id']);
            $table->dropForeign(['physical_server_source_id']);
            $table->dropForeign(['storage_device_source_id']);
            $table->dropForeign(['workstation_source_id']);
            $table->dropForeign(['physical_security_device_source_id']);

            $table->dropForeign(['logical_server_dest_id']);
            $table->dropForeign(['peripheral_dest_id']);
            $table->dropForeign(['physical_server_dest_id']);
            $table->dropForeign(['storage_device_dest_id']);
            $table->dropForeign(['workstation_dest_id']);
            $table->dropForeign(['physical_security_device_dest_id']);

            $table->dropColumn([
                'logical_server_source_id',
                'peripheral_source_id',
                'physical_server_source_id',
                'storage_device_source_id',
                'workstation_source_id',
                'physical_security_device_source_id',
                'logical_server_dest_id',
                'peripheral_dest_id',
                'physical_server_dest_id',
                'storage_device_dest_id',
                'workstation_dest_id',
                'physical_security_device_dest_id',
            ]);
        });
    }
};
