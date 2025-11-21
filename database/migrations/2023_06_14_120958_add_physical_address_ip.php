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
        Schema::table('physical_servers', function (Blueprint $table) {
            $table->string('address_ip')->nullable();
        });

        Schema::table('storage_devices', function (Blueprint $table) {
            $table->string('address_ip')->nullable();
        });

        Schema::table('peripherals', function (Blueprint $table) {
            $table->string('address_ip')->nullable();
        });

        Schema::table('wifi_terminals', function (Blueprint $table) {
            $table->string('address_ip')->nullable();
        });

        Schema::table('phones', function (Blueprint $table) {
            $table->string('address_ip')->nullable();
        });

        Schema::table('physical_security_devices', function (Blueprint $table) {
            $table->string('address_ip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('physical_servers', function (Blueprint $table) {
            $table->dropColumn(['address_ip']);
        });

        Schema::table('storage_devices', function (Blueprint $table) {
            $table->dropColumn(['address_ip']);
        });

        Schema::table('peripherals', function (Blueprint $table) {
            $table->dropColumn(['address_ip']);
        });

        Schema::table('wifi_terminals', function (Blueprint $table) {
            $table->dropColumn(['address_ip']);
        });

        Schema::table('phones', function (Blueprint $table) {
            $table->dropColumn(['address_ip']);
        });

        Schema::table('physical_security_devices', function (Blueprint $table) {
            $table->dropColumn(['address_ip']);
        });
    }
};
