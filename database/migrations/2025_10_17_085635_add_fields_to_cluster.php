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
        Schema::table('clusters', function (Blueprint $table) {
            $table->string('attributes')->nullable()->after('type');
            $table->unsignedInteger('icon_id')
                ->after('attributes')
                ->nullable()
                ->index('document_id_fk_495432841');
            $table->foreign('icon_id', 'document_id_fk_495432841')
                ->references('id')
                ->on('documents');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clusters', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('document_id_fk_495432841');
            }
            $table->dropColumn('icon_id');
            $table->dropColumn('attributes');
        });
    }
};
