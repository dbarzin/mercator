<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // DDL non transactionnel pour SQLite & co
    public $withinTransaction = false;

    public function up(): void
    {
        // 1) Table pivot
        Schema::create('cluster_router', function (Blueprint $table) {
            $table->unsignedInteger('cluster_id');
            $table->unsignedInteger('router_id');

            $table->primary(['cluster_id', 'router_id']);   // évite les doublons
            $table->index('router_id');

            $table->foreign('cluster_id')
                ->references('id')->on('clusters')
                ->onDelete('cascade');

            $table->foreign('router_id')
                ->references('id')->on('routers')
                ->onDelete('cascade');
        });

        // 2) Backfill: routers.cluster_id -> pivot
        DB::table('routers')
            ->whereNotNull('cluster_id')
            ->orderBy('id')
            ->chunkById(1000, function ($rows) {
                $inserts = [];
                foreach ($rows as $row) {
                    $inserts[] = [
                        'cluster_id' => $row->cluster_id,
                        'router_id'  => $row->id,
                    ];
                }
                if ($inserts) {
                    DB::table('cluster_router')->insertOrIgnore($inserts);
                }
            });

        // 3) Drop FK / index / colonne `cluster_id` sur routers
        if (Schema::hasColumn('routers', 'cluster_id')) {
            Schema::disableForeignKeyConstraints();

            // 3.1 – tenter de supprimer la FK (par colonne puis par noms hérités)
            try {
                Schema::table('routers', function (Blueprint $table) {
                    $table->dropForeign(['cluster_id']); // portable
                });
            } catch (\Throwable $e) {}
            try {
                Schema::table('routers', function (Blueprint $table) {
                    $table->dropForeign('cluster_id_fk_4398834'); // ancien nom éventuel
                });
            } catch (\Throwable $e) {}

            // 3.2 – supprimer les index liés (par NOM et par COLONNE)
            try {
                Schema::table('routers', function (Blueprint $table) {
                    $table->dropIndex('cluster_id_fk_4398834'); // index historique éventuel
                });
            } catch (\Throwable $e) {}
            try {
                Schema::table('routers', function (Blueprint $table) {
                    $table->dropIndex(['cluster_id']); // ex: routers_cluster_id_index
                });
            } catch (\Throwable $e) {}
            try {
                Schema::table('routers', function (Blueprint $table) {
                    $table->dropIndex('routers_cluster_id_index'); // convention Laravel MySQL
                });
            } catch (\Throwable $e) {}

            // 3.3 – SQLite: purge défensive de tout index qui contient "cluster_id"
            if (DB::getDriverName() === 'sqlite') {
                $indexes = DB::select("PRAGMA index_list('routers')");
                foreach ($indexes as $idx) {
                    if (isset($idx->name) && stripos($idx->name, 'cluster_id') !== false) {
                        DB::statement('DROP INDEX IF EXISTS "'.$idx->name.'"');
                    }
                }
            }

            // 3.4 – drop de la colonne
            Schema::table('routers', function (Blueprint $table) {
                $table->dropColumn('cluster_id');
            });

            Schema::enableForeignKeyConstraints();
        }
    }

    public function down(): void
    {
        // 1) Recréer la colonne (nullable) + index (laisser Laravel nommer)
        if (!Schema::hasColumn('routers', 'cluster_id')) {
            Schema::table('routers', function (Blueprint $table) {
                $table->unsignedInteger('cluster_id')->nullable();
                $table->index('cluster_id'); // portable
            });
        }

        // 2) Backfill inverse: si plusieurs clusters pour un router, prendre MIN(cluster_id)
        $pairs = DB::table('cluster_router')
            ->select('router_id', DB::raw('MIN(cluster_id) AS cluster_id'))
            ->groupBy('router_id')
            ->get();

        foreach ($pairs as $p) {
            DB::table('routers')
                ->where('id', $p->router_id)
                ->update(['cluster_id' => $p->cluster_id]);
        }

        // 3) Reposer la FK proprement (sans nom custom)
        Schema::table('routers', function (Blueprint $table) {
            $table->foreign('cluster_id')
                ->references('id')->on('clusters')
                ->onDelete('cascade');
        });

        // 4) Drop de la table pivot
        Schema::dropIfExists('cluster_router');
    }
};
