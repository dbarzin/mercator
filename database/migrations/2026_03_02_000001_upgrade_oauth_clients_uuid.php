// database/migrations/2026_03_02_000001_upgrade_oauth_clients_uuid.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Vérifier si la colonne id est déjà en varchar/uuid
        $columnType = DB::select("
            SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'oauth_clients'
            AND COLUMN_NAME = 'id'
        ")[0]->DATA_TYPE ?? null;

        if ($columnType === 'int' || $columnType === 'bigint') {
            // Supprimer les FK qui référencent oauth_clients.id
            Schema::table('oauth_access_tokens', function (Blueprint $table) {
                $table->string('client_id', 100)->change();
            });
            Schema::table('oauth_auth_codes', function (Blueprint $table) {
                $table->string('client_id', 100)->change();
            });

            // Vider les tables (les tokens existants sont de toute façon invalides)
            DB::table('oauth_access_tokens')->truncate();
            DB::table('oauth_auth_codes')->truncate();
            DB::table('oauth_clients')->truncate();
            DB::table('oauth_personal_access_clients')->truncate();
            DB::table('oauth_refresh_tokens')->truncate();

            // Modifier la colonne id
            Schema::table('oauth_clients', function (Blueprint $table) {
                $table->string('id', 100)->change();
            });
        }
    }

    public function down(): void
    {
        // Pas de rollback sur cette migration
    }
};

