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
            $table->string('cpu')->nullable();
            $table->string('memory')->nullable();
            $table->string('disk')->nullable();
            $table->string('disk_used')->nullable();
            $table->string('operating_system')->nullable();
            $table->dateTime('install_date')->nullable();
            $table->dateTime('update_date')->nullable();
        });

        Schema::table('logical_servers', function (Blueprint $table) {
            $table->integer('disk_used')->after('disk')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('physical_servers', function (Blueprint $table) {
            $table->dropColumn(['cpu']);
            $table->dropColumn(['memory']);
            $table->dropColumn(['disk']);
            $table->dropColumn(['disk_used']);
            $table->dropColumn(['operating_system']);
            $table->dropColumn(['install_date']);
            $table->dropColumn(['update_date']);
        });

        Schema::table('logical_servers', function (Blueprint $table) {
            $table->dropColumn(['disk_used']);
        });
    }
};
