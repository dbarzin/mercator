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
            $table->unsignedBigInteger('security_device_source_id')
                ->nullable()
                ->after('physical_security_device_source_id')
                ->references('id')->on('security_devices')
                ->onDelete('set null');

            $table->unsignedBigInteger('security_device_dest_id')
                ->nullable()
                ->after('physical_security_device_dest_id')
                ->references('id')->on('security_devices')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logical_flows', function (Blueprint $table) {
            $table->dropColumn('security_device_source_id');
            $table->dropColumn('security_device_dest_id');
        });
    }
};
