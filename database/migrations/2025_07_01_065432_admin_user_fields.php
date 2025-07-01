<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('admin_users', function (Blueprint $table) {
            $table->string('attributes')->nullable()->after('type');
        });

        // Met à jour les données existantes
        DB::table('admin_users')->select('id', 'local', 'privileged')->orderBy('id')->chunk(100, function ($users) {
            foreach ($users as $user) {
                $attrs = [];
                if ($user->local) {
                    $attrs[] = 'local';
                }
                if ($user->privileged) {
                    $attrs[] = 'privileged';
                }

                DB::table('admin_users')
                    ->where('id', $user->id)
                    ->update(['attributes' => implode(',', $attrs)]);
            }
        });

        Schema::table('admin_users', function (Blueprint $table) {
            $table->dropColumn(['local', 'privileged']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_users', function (Blueprint $table) {
            $table->tinyInteger('local')->nullable()->after('description');
            $table->tinyInteger('privileged')->nullable()->after('local');
        });

        // Restaure les données depuis 'attributes'
        DB::table('admin_users')->select('id', 'attributes')->orderBy('id')->chunk(100, function ($users) {
            foreach ($users as $user) {
                $attrs = explode(',', $user->attributes ?? '');
                DB::table('admin_users')
                    ->where('id', $user->id)
                    ->update([
                        'local' => in_array('local', $attrs) ? 1 : null,
                        'privileged' => in_array('privileged', $attrs) ? 1 : null,
                    ]);
            }
        });

        Schema::table('admin_users', function (Blueprint $table) {
            $table->dropColumn('attributes');
        });
    }
};
