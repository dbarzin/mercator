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
        // Remove link to physical_switch
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('storage_devices', function (Blueprint $table) {
                $table->dropForeign('physical_switch_fk_4025543');
                $table->dropColumn('physical_switch_id');
            });
        }

        // Add type
        Schema::table('storage_devices', function (Blueprint $table) {
            $table->string('type')->after('name')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('storage_devices', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('storage_devices', function (Blueprint $table) {
                $table->unsignedInteger('physical_switch_id')->nullable()->index('physical_switch_fk_4025543');
            });

            Schema::table('storage_devices', function (Blueprint $table) {
                $table->foreign('physical_switch_id', 'physical_switch_fk_4025543')->references('id')->on('physical_switches')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            });

        }
    }
};
