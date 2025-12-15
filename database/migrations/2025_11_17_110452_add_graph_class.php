<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('graphs', function (Blueprint $table) {
            $table->integer('class')->nullable()->after('id');
        });

        // Mettre à jour toutes les lignes existantes avec class = 1
        DB::table('graphs')->update(['class' => 1]);
    }

    public function down(): void
    {
        Schema::table('graphs', function (Blueprint $table) {
            $table->dropColumn('class');
        });
    }
};
