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
        Schema::table('logical_servers', function (Blueprint $table) {
            $table->unsignedInteger('icon_id')->after('name')->nullable()->index('document_id_fk_51303394');;
            $table->foreign('icon_id','document_id_fk_51303394')->references('id')->on('documents');
        });

        Schema::table('physical_servers', function (Blueprint $table) {
            $table->unsignedInteger('icon_id')->after('name')->nullable()->index('document_id_fk_5328384');
            $table->foreign('icon_id','document_id_fk_5328384')->references('id')->on('documents');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logical_servers', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('document_id_fk_51303394');
            }
        });

        Schema::table('logical_servers', function (Blueprint $table) {
            $table->dropColumn('icon_id');
        });

        Schema::table('physical_servers', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('document_id_fk_5328384');
            }
        });

        Schema::table('physical_servers', function (Blueprint $table) {
            $table->dropColumn('icon_id');
        });
    }
};
