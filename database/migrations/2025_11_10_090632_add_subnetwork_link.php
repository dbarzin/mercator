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
        Schema::table('subnetworks', function (Blueprint $table) {
            $table->unsignedInteger('subnetwork_id')
                ->nullable()
                ->after('network_id');
            $table->index('subnetwork_id');
            $table->foreign('subnetwork_id')
                ->references('id')
                ->on('subnetworks')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Si la colonne n'existe pas, rien à faire
        if (! Schema::hasColumn('subnetworks', 'subnetwork_id')) {
            return;
        }
        Schema::table('subnetworks', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['subnetwork_id']);
                $table->dropIndex(['subnetwork_id']);
            }
            $table->dropColumn('subnetwork_id');
        });
    }
};
