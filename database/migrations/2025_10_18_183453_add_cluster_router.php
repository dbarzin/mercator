<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        // 1) Table pivot
        Schema::create('cluster_router', function (Blueprint $table) {
            $table->unsignedInteger('cluster_id');
            $table->unsignedInteger('router_id');

            $table->primary(['cluster_id', 'router_id']); // empêche les doublons
            $table->index('router_id');

            $table->foreign('cluster_id')
                ->references('id')->on('clusters')
                ->onDelete('cascade');

            $table->foreign('router_id')
                ->references('id')->on('routers')
                ->onDelete('cascade');
        });

        // 2) Backfill : copier routers.cluster_id -> pivot
        // On ignore les lignes où cluster_id est NULL
        DB::table('routers')
            ->whereNotNull('cluster_id')
            ->orderBy('id')
            ->chunkById(1000, function ($rows) {
                $now = now();
                $inserts = [];
                foreach ($rows as $row) {
                    $inserts[] = [
                        'cluster_id'        => $row->cluster_id,
                        'router_id' => $row->id,
                    ];
                }
                if (!empty($inserts)) {
                    // insertOrIgnore pour éviter tout doublon éventuel
                    DB::table('cluster_router')->insertOrIgnore($inserts);
                }
            });

        // 3) Suppression contrainte FK/index/colonne cluster_id sur routers

        // Certaines anciennes migrations peuvent avoir des noms de contraintes différents.
        // On tente proprement : d'abord dropForeign via tableau, sinon on ignore.
        Schema::table('routers', function (Blueprint $table) {
            // Si la contrainte existe, ceci fonctionne ; sinon Laravel lèvera une exception.
            // On encapsule donc dans un try/catch global.
        });

        try {
            Schema::table('routers', function (Blueprint $table) {
                // Essaie de supprimer la contrainte si elle existe
                $table->dropIndex(['cluster_id_fk_4398834']);
            });
        } catch (\Throwable $e) {
            // pas de FK ou nom différent : on ignore
        }

        try {
            Schema::table('routers', function (Blueprint $table) {
                // Supprime l'index si présent (MUL peut n'être qu'un index)
                $table->dropForeign('cluster_id_fk_4398834');
            });
        } catch (\Throwable $e) {
            // pas d'index : on ignore
        }

        // Enfin, suppression de la colonne
        if (Schema::hasColumn('routers', 'cluster_id')) {
            Schema::table('routers', function (Blueprint $table) {
                $table->dropColumn('cluster_id');
            });
        }
    }

    public function down(): void
    {
        // 1) Recréation cluster_id (nullable)
        Schema::table('routers', function (Blueprint $table) {
            // link to cluster
            $table->unsignedInteger('cluster_id')->index('cluster_id_fk_4398834')->nullable();
        });


        // 2) Backfill inverse : si plusieurs clusters, on choisit le MIN(cluster_id)
        // (règle simple et déterministe)
        $pairs = DB::table('cluster_router')
            ->select('router_id', DB::raw('MIN(cluster_id) AS cluster_id'))
            ->groupBy('router_id')
            ->get();

        foreach ($pairs as $p) {
            DB::table('routers')
                ->where('id', $p->router_id)
                ->update(['cluster_id' => $p->cluster_id]);
        }

        // 3) Contrainte FK/Index (optionnel mais propre)
        Schema::table('routers', function (Blueprint $table) {
            $table->foreign('cluster_id', 'cluster_id_fk_4398834')->references('id')->on('clusters')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        // 4) Suppression de la table pivot
        Schema::dropIfExists('cluster_router');
    }
};
