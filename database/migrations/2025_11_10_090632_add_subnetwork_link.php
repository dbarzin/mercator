<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add a nullable unsigned `subnetwork_id` column to the `subnetworks` table and create its index and foreign key.
     *
     * The new `subnetwork_id` column is placed after `network_id`, indexed, and constrained to `subnetworks.id` with
     * `ON DELETE SET NULL` behavior.
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
     * Reverts the migration by removing the `subnetwork_id` column and its associated index and foreign key from the `subnetworks` table.
     *
     * If the `subnetwork_id` column does not exist, the method returns without making changes. On SQLite, foreign key and index removal are skipped and only the column is dropped.
     *
     * @return void
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