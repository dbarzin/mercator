<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!$this->isUuidColumn()) {
            // Vider les tables (tokens incompatibles Passport 12 → 13)
            DB::table('oauth_refresh_tokens')->delete();
            DB::table('oauth_access_tokens')->delete();
            DB::table('oauth_auth_codes')->delete();
            DB::table('oauth_personal_access_clients')->delete();
            DB::table('oauth_clients')->delete();

            // Adapter client_id dans les tables liées
            Schema::table('oauth_access_tokens', function (Blueprint $table) {
                $table->string('client_id', 100)->change();
            });
            Schema::table('oauth_auth_codes', function (Blueprint $table) {
                $table->string('client_id', 100)->change();
            });

            // Modifier la colonne id d'oauth_clients
            Schema::table('oauth_clients', function (Blueprint $table) {
                $table->string('id', 100)->change();
            });
        }
    }

    public function down(): void
    {
        // Pas de rollback
    }

    private function isUuidColumn(): bool
    {
        $connection = DB::connection();
        $driver = $connection->getDriverName();
        $table = 'oauth_clients';

        return match ($driver) {
            'mysql', 'mariadb' => $this->isUuidMySQL($table),
            'pgsql'            => $this->isUuidPostgreSQL($table),
            'sqlite'           => $this->isUuidSQLite($table),
            default            => false,
        };
    }

    private function isUuidMySQL(string $table): bool
    {
        $col = DB::selectOne("
            SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = ?
              AND COLUMN_NAME = 'id'
        ", [$table]);

        return $col && in_array(strtolower($col->DATA_TYPE), ['varchar', 'char', 'uuid']);
    }

    private function isUuidPostgreSQL(string $table): bool
    {
        $col = DB::selectOne("
            SELECT data_type FROM information_schema.columns
            WHERE table_name = ?
              AND column_name = 'id'
        ", [$table]);

        return $col && in_array(strtolower($col->data_type), ['character varying', 'uuid', 'text']);
    }

    private function isUuidSQLite(string $table): bool
    {
        $cols = DB::select("PRAGMA table_info({$table})");
        foreach ($cols as $col) {
            if ($col->name === 'id') {
                return in_array(strtoupper($col->type), ['VARCHAR', 'TEXT', 'UUID', 'CHAR']);
            }
        }
        return false;
    }
};

