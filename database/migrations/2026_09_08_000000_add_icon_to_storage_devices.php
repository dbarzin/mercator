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
        Schema::table('storage_devices', function (Blueprint $table) {
            $table->unsignedInteger('icon_id')->after('name')->nullable()->index('document_id_fk_53812733');
            $table->foreign('icon_id', 'document_id_fk_53812733')->references('id')->on('documents');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('storage_devices', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('document_id_fk_53812733');
            }
        });

        Schema::table('storage_devices', function (Blueprint $table) {
            $table->dropColumn('icon_id');
        });
    }
};
