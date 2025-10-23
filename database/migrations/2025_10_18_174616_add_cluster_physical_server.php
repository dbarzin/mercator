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
        Schema::create('cluster_physical_server', function (Blueprint $table) {
            $table->unsignedInteger('cluster_id');
            $table->unsignedInteger('physical_server_id');

            $table->primary(['cluster_id', 'physical_server_id']); // empêche les doublons
            $table->index('physical_server_id');

            $table->foreign('cluster_id')
                ->references('id')->on('clusters')
                ->onDelete('cascade');

            $table->foreign('physical_server_id')
                ->references('id')->on('physical_servers')
                ->onDelete('cascade');
        });

        // 2) Backfill : copier physical_servers.cluster_id -> pivot
        // On ignore les lignes où cluster_id est NULL
        DB::table('physical_servers')
            ->whereNotNull('cluster_id')
            ->orderBy('id')
            ->chunkById(1000, function ($rows) {
                $now = now();
                $inserts = [];
                foreach ($rows as $row) {
                    $inserts[] = [
                        'cluster_id'        => $row->cluster_id,
                        'physical_server_id' => $row->id,
                    ];
                }
                if (!empty($inserts)) {
                    // insertOrIgnore pour éviter tout doublon éventuel
                    DB::table('cluster_physical_server')->insertOrIgnore($inserts);
                }
            });

        // 3) Suppression contrainte FK/index/colonne cluster_id sur physical_servers

        // Certaines anciennes migrations peuvent avoir des noms de contraintes différents.
        // On tente proprement : d'abord dropForeign via tableau, sinon on ignore.
        Schema::table('physical_servers', function (Blueprint $table) {
            // Si la contrainte existe, ceci fonctionne ; sinon Laravel lèvera une exception.
            // On encapsule donc dans un try/catch global.
        });

        try {
            Schema::table('physical_servers', function (Blueprint $table) {
                // Essaie de supprimer la contrainte si elle existe
                $table->dropIndex(['cluster_id_fk_5438543']);
            });
        } catch (\Throwable $e) {
            // pas de FK ou nom différent : on ignore
        }

        try {
            Schema::table('physical_servers', function (Blueprint $table) {
                // Supprime l'index si présent (MUL peut n'être qu'un index)
                $table->dropForeign('cluster_id_fk_5438543');
            });
        } catch (\Throwable $e) {
            // pas d'index : on ignore
        }

        // Enfin, suppression de la colonne
        if (Schema::hasColumn('physical_servers', 'cluster_id')) {
            Schema::table('physical_servers', function (Blueprint $table) {
                $table->dropColumn('cluster_id');
            });
        }
    }

    public function down(): void
    {
        // 1) Recréation cluster_id (nullable)
        Schema::table('physical_servers', function (Blueprint $table) {
            // link to cluster
            $table->unsignedInteger('cluster_id')->index('cluster_id_fk_5435359')->nullable();
        });


        // 2) Backfill inverse : si plusieurs clusters, on choisit le MIN(cluster_id)
        // (règle simple et déterministe)
        $pairs = DB::table('cluster_physical_server')
            ->select('physical_server_id', DB::raw('MIN(cluster_id) AS cluster_id'))
            ->groupBy('physical_server_id')
            ->get();

        foreach ($pairs as $p) {
            DB::table('physical_servers')
                ->where('id', $p->physical_server_id)
                ->update(['cluster_id' => $p->cluster_id]);
        }

        // 3) Contrainte FK/Index (optionnel mais propre)
        Schema::table('physical_servers', function (Blueprint $table) {
            $table->foreign('cluster_id', 'cluster_id_fk_5438543')->references('id')->on('clusters')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        // 4) Suppression de la table pivot
        Schema::dropIfExists('cluster_physical_server');
    }
};
