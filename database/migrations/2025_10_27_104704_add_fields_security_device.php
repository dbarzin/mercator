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
        Schema::table('physical_security_devices', function (Blueprint $table) {
            $table->string('attributes')->nullable()->after('type');
            $table->unsignedInteger('icon_id')
                ->after('attributes')
                ->nullable()
                ->index();
            $table->foreign('icon_id')
                ->references('id')
                ->on('documents');
        });

        Schema::table('security_devices', function (Blueprint $table) {
            $table->string('type')->nullable()->after('name');
            $table->string('attributes')->nullable()->after('type');
            $table->unsignedInteger('icon_id')
                ->after('attributes')
                ->nullable()
                ->index();
            $table->foreign('icon_id')
                ->references('id')
                ->on('documents');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('physical_security_devices', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('icon_id');
            }
            $table->dropColumn('icon_id');
            $table->dropColumn('attributes');
        });

        Schema::table('security_devices', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('icon_id');
            }
            $table->dropColumn('icon_id');
            $table->dropColumn('attributes');
            $table->dropColumn('type');
        });
    }
};
