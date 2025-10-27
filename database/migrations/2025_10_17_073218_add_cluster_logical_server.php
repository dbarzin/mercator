<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Indique si la migration doit être exécutée dans une transaction automatique.
     *
     * - true : Laravel exécute la migration dans une transaction si le SGBD le permet (PostgreSQL, SQLite...).
     *          Cela garantit que toutes les modifications sont annulées en cas d'erreur (atomicité).
     * - false : Laravel exécute la migration hors transaction.
     *           Utilisez cette option lorsqu'une opération DDL ou spécifique (ex : certains index ou contraintes sur MySQL)
     *           n'est pas compatible avec le mode transactionnel, afin d'éviter les erreurs du SGBD.
     *
     * Exemple d’usage :
     *    public $withinTransaction = false; // Désactive la transaction automatique pour cette migration.
     *
     * Référence officielle :
     * https://api.laravel.com/docs/12.x/Illuminate/Database/Migrations/Migration.html
     */
    public $withinTransaction = false;

    public function up(): void
    {

        // 1) Table pivot
        Schema::create('cluster_logical_server', function (Blueprint $table) {
            $table->unsignedInteger('cluster_id');
            $table->unsignedInteger('logical_server_id');

            $table->primary(['cluster_id', 'logical_server_id']); // empêche les doublons
            $table->index('logical_server_id');

            $table->foreign('cluster_id')
                ->references('id')->on('clusters')
                ->onDelete('cascade');

            $table->foreign('logical_server_id')
                ->references('id')->on('logical_servers')
                ->onDelete('cascade');
        });

        // 2) Backfill : copier logical_servers.cluster_id -> pivot
        // On ignore les lignes où cluster_id est NULL
        DB::table('logical_servers')
            ->whereNotNull('cluster_id')
            ->orderBy('id')
            ->chunkById(1000, function ($rows) {
                $now = now();
                $inserts = [];
                foreach ($rows as $row) {
                    $inserts[] = [
                        'cluster_id'        => $row->cluster_id,
                        'logical_server_id' => $row->id,
                    ];
                }
                if (!empty($inserts)) {
                    // insertOrIgnore pour éviter tout doublon éventuel
                    DB::table('cluster_logical_server')->insertOrIgnore($inserts);
                }
            });

        // 3) Suppression contrainte FK/index/colonne cluster_id sur logical_servers
        Schema::table('logical_servers', function (Blueprint $table) {
            $table->dropForeign('cluster_id_fk_5435359');
        });

        // Enfin, suppression de la colonne
        if (Schema::hasColumn('logical_servers', 'cluster_id')) {
            Schema::table('logical_servers', function (Blueprint $table) {
                try {
                    Schema::table('logical_servers', function (Blueprint $table) {
                        $table->dropForeign(['cluster_id']); // drop FK by column name, no constraint name
                      });
                } catch (\Illuminate\Database\QueryException $e) {
                      // Constraint does not exist or already dropped, ignore
                } // use array notation with column name, not constraint name

                try {
                    Schema::table('logical_servers', function (Blueprint $table) {
                        $table->dropIndex(['cluster_id']);
                    });
                } catch (\Illuminate\Database\QueryException $e) {
                    // Index does not exist, ignore
                } // similarly for index
                $table->dropColumn('cluster_id');
            });
        }
    }

    public function down(): void
    {
        // 1) Recréation cluster_id (nullable)
        Schema::table('logical_servers', function (Blueprint $table) {
            // link to cluster
            $table->unsignedInteger('cluster_id')->index('cluster_id_fk_5435359')->nullable();
        });


        // 2) Backfill inverse : si plusieurs clusters, on choisit le MIN(cluster_id)
        // (règle simple et déterministe)
        $pairs = DB::table('cluster_logical_server')
            ->select('logical_server_id', DB::raw('MIN(cluster_id) AS cluster_id'))
            ->groupBy('logical_server_id')
            ->get();

        foreach ($pairs as $p) {
            DB::table('logical_servers')
                ->where('id', $p->logical_server_id)
                ->update(['cluster_id' => $p->cluster_id]);
        }

        // 3) Contrainte FK/Index (optionnel mais propre)
        Schema::table('logical_servers', function (Blueprint $table) {
            $table->foreign('cluster_id', 'cluster_id_fk_5435359')->references('id')->on('clusters')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        // 4) Suppression de la table pivot
        Schema::dropIfExists('cluster_logical_server');
    }
};
