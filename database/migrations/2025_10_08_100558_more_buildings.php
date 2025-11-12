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
        Schema::table('buildings', function (Blueprint $table) {
            $table->string('type')->nullable()->after('name');
            $table->unsignedInteger('building_id')
                ->nullable()
                ->after('site_id')
                ->index('building_id_fk_94821232');
            $table->foreign('building_id')
                ->references('id')
                ->on('buildings')
                ->onDelete('set null');
            $table->unsignedInteger('icon_id')
                ->after('building_id')
                ->nullable()
                ->index('document_id_fk_49574431');
            $table->foreign('icon_id', 'document_id_fk_49574431')
                ->references('id')
                ->on('documents');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buildings', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('building_id_fk_94821232');
                $table->dropForeign('document_id_fk_49574431');
            }
            $table->dropColumn('building_id');
            $table->dropColumn('icon_id');
            $table->dropColumn('type');
        });
    }
};
