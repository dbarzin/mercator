<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // SQLite ne gère pas les DDL en transaction -> off
    public $withinTransaction = false;

    public function up(): void
    {
        // 1) Table pivot
        Schema::create('cluster_physical_server', function (Blueprint $table) {
            $table->unsignedInteger('cluster_id');
            $table->unsignedInteger('physical_server_id');

            $table->primary(['cluster_id', 'physical_server_id']);
            $table->index('physical_server_id');

            $table->foreign('cluster_id')
                ->references('id')->on('clusters')
                ->onDelete('cascade');

            $table->foreign('physical_server_id')
                ->references('id')->on('physical_servers')
                ->onDelete('cascade');
        });

        // 2) Backfill depuis physical_servers.cluster_id
        DB::table('physical_servers')
            ->whereNotNull('cluster_id')
            ->orderBy('id')
            ->chunkById(1000, function ($rows) {
                $inserts = [];
                foreach ($rows as $row) {
                    $inserts[] = [
                        'cluster_id'          => $row->cluster_id,
                        'physical_server_id'  => $row->id,
                    ];
                }
                if ($inserts) {
                    DB::table('cluster_physical_server')->insertOrIgnore($inserts);
                }
            });

        // 3) Suppression propre de la FK, des index et de la colonne `cluster_id`
        if (Schema::hasColumn('physical_servers', 'cluster_id')) {
            Schema::disableForeignKeyConstraints();

            // 3.1 Drop FK (essayer par colonnes + par noms possibles)
            try {
                Schema::table('physical_servers', function (Blueprint $table) {
                    $table->dropForeign(['cluster_id']); // portable
                });
            } catch (\Throwable $e) {}
            try {
                Schema::table('physical_servers', function (Blueprint $table) {
                    $table->dropForeign('cluster_id_fk_5438543'); // ancien nom custom éventuel
                });
            } catch (\Throwable $e) {}

            // 3.2 Drop index (IMPORTANT: par NOM d'index quand on le connaît)
            try {
                Schema::table('physical_servers', function (Blueprint $table) {
                    $table->dropIndex('cluster_id_fk_5438543'); // l'index qui casse chez toi
                });
            } catch (\Throwable $e) {}

            // essayer aussi les conventions courantes
            try {
                Schema::table('physical_servers', function (Blueprint $table) {
                    $table->dropIndex(['cluster_id']); // ex: physical_servers_cluster_id_index
                });
            } catch (\Throwable $e) {}
            try {
                Schema::table('physical_servers', function (Blueprint $table) {
                    $table->dropIndex('physical_servers_cluster_id_index');
                });
            } catch (\Throwable $e) {}

            // 3.3 SQLite: purge défensive de tout index contenant "cluster_id"
            if (DB::getDriverName() === 'sqlite') {
                $indexes = DB::select("PRAGMA index_list('physical_servers')");
                foreach ($indexes as $idx) {
                    // $idx->name (string), $idx->origin (c, u, pk)
                    if (isset($idx->name) && stripos($idx->name, 'cluster_id') !== false) {
                        DB::statement('DROP INDEX IF EXISTS "'.$idx->name.'"');
                    }
                }
            }

            // 3.4 Enfin, suppression de la colonne
            Schema::table('physical_servers', function (Blueprint $table) {
                $table->dropColumn('cluster_id');
            });

            Schema::enableForeignKeyConstraints();
        }
    }

    public function down(): void
    {
        // 1) Recréer la colonne (nullable) + index
        if (!Schema::hasColumn('physical_servers', 'cluster_id')) {
            Schema::table('physical_servers', function (Blueprint $table) {
                $table->unsignedInteger('cluster_id')->nullable();
                $table->index('cluster_id'); // portable
            });
        }

        // 2) Backfill inverse: MIN(cluster_id) si plusieurs
        $pairs = DB::table('cluster_physical_server')
            ->select('physical_server_id', DB::raw('MIN(cluster_id) AS cluster_id'))
            ->groupBy('physical_server_id')
            ->get();

        foreach ($pairs as $p) {
            DB::table('physical_servers')
                ->where('id', $p->physical_server_id)
                ->update(['cluster_id' => $p->cluster_id]);
        }

        // 3) Reposer la FK (nom laissé au SGBD)
        Schema::table('physical_servers', function (Blueprint $table) {
            $table->foreign('cluster_id')
                ->references('id')->on('clusters')
                ->onDelete('cascade');
        });

        // 4) Drop de la table pivot
        Schema::dropIfExists('cluster_physical_server');
    }
};
